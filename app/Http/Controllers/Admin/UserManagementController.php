<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
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
}
