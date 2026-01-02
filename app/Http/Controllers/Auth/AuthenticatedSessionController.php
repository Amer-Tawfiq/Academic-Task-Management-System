<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // 🔁 التوجيه حسب الدور
    if ($user->role_id == 1) {
        // مدرس
        return redirect()->route('reports.index');
    } elseif ($user->role_id == 2) {
        // رئيس قسم
        return redirect()->route('courses.index');
    } elseif ($user->role_id == 3) {
        // عميد
        return redirect()->route('dashboard');
    }
    return redirect('/');

    }

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
