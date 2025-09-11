<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();

        // เด้งตาม role
        return match ($user->role) {
            'admin'    => redirect()->route('admin.index'),
            'seller'   => redirect()->route('seller.index'),
            'customer' => redirect()->route('customer.shop'),
            default    => redirect()->route('dashboard'),
        };
    }
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();
    //     $request->session()->regenerate();

    //     $role = $request->user()->role;

    //     // fallback ปลอดภัย: เลือกปลายทางตาม role ถ้ามี route นั้นจริง
    //     if ($role === 'admin' && Route::has('admin.index')) {
    //         return redirect()->intended(route('admin.index', absolute: false));
    //     }

    //     if ($role === 'seller' && Route::has('seller.index')) {
    //         return redirect()->intended(route('seller.index', absolute: false));
    //     }

    //     // ลูกค้า → ส่งไปหน้าช้อป (ใช้ shop.index ที่เราสร้างไว้)
    //     if ($role === 'customer' && Route::has('shop.index')) {
    //         return redirect()->intended(route('shop.index', absolute: false));
    //     }

    //     // สำรองสุดท้าย: กลับ dashboard หรือ /
    //     return redirect()->intended(route('dashboard', absolute: false));
    // }
    

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
