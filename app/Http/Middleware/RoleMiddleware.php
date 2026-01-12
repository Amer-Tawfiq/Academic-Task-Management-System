<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roleName)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // تحميل علاقة الدور
        $user->load('role');
        
        // التحقق من وجود الدور
        if (!$user->role) {
            abort(403, 'المستخدم ليس لديه دور معين');
        }

        // مقارنة بدون مراعاة الحالة (case-insensitive)
        if (strtolower($user->role->role_name) !== strtolower($roleName)) {
            abort(403, 'الدور غير مطابق: لديك دور ' . $user->role->role_name . ' والمطلوب ' . $roleName);
        }

        return $next($request);
    }
}