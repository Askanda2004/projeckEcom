<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    // แสดงตารางผู้ใช้
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));

        $pk = (new \App\Models\User)->getKeyName();

        $users = User::query()
            ->when($q !== '', function($s) use ($q) {
                $s->where(function($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%")
                      ->orWhere('role','like',"%{$q}%");
                });
            })
            ->orderByDesc($pk)
            ->paginate(10)
            ->withQueryString();

        $filterRole = null; // ใช้บอก active ใน sidebar
        return view('admin.users.index', compact('users','q','filterRole'));
    }

    // (ทางเลือก) ฟอร์มแก้ไข
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // (ทางเลือก) อัปเดต role/ข้อมูลพื้นฐาน
    public function update(Request $request, \App\Models\User $user)
    {
        $validated = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'role'  => ['required','in:admin,seller,customer'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.index')->with('status','User updated.');
    }

    // (ทางเลือก) ลบผู้ใช้
    public function byRole(Request $request, string $role)
    {
        $request->validate([
            'role' => [Rule::in(['customer','seller','admin'])]
        ]);

        $q = trim($request->get('q',''));

        $pk = (new \App\Models\User)->getKeyName();

        $users = User::query()
            ->where('role', $role)
            ->when($q !== '', function($s) use ($q) {
                $s->where(function($w) use ($q) {
                    $w->where('name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%");
                });
            })
            ->orderByDesc($pk)
            ->paginate(10)
            ->withQueryString();

        $filterRole = $role; // ใช้บอก active ใน sidebar
        return view('admin.users.index', compact('users','q','filterRole'));
    }

    public function destroy(User $user): RedirectResponse
    {
        // อนุญาตเฉพาะแอดมิน (ถ้าใช้ middleware อยู่แล้ว สามารถข้ามได้)
        if (! auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'เฉพาะผู้ดูแลระบบเท่านั้นที่เข้าถึงได้');
        }

        // กันลบตัวเอง
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถลบบัญชีของตัวเองได้');
        }

        // ถ้าผู้ใช้มี role = admin ให้กันไม่ให้ลบจนเหลือ 0
        $isTargetAdmin = $user->role === 'admin';

        if ($isTargetAdmin) {
            $adminCount = User::query()
                ->when(method_exists(User::class, 'role'), fn ($q) => $q->role('admin'), function ($q) {
                    $q->where('role', 'admin'); // กรณีเก็บในคอลัมน์ role
                })
                ->count();

            if ($adminCount <= 1) {
                return back()->with('error', 'ไม่สามารถลบแอดมินคนสุดท้ายได้');
            }
        }

        // ถ้าใช้ SoftDeletes: $user->delete();
        // ถ้าต้องการลบถาวร: $user->forceDelete();
        DB::transaction(function () use ($user) {
            $user->delete();
        });

        return back()->with('status', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }
}
