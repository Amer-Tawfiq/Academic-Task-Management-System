<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class SettingsController extends Controller
{
    /**
     * عرض صفحة الإعدادات الشخصية
     */
    public function index()
    {
        $user = Auth::user();
        
        // تحديد نوع المستخدم لعرض القائمة المناسبة
        $userType = $this->getUserType($user);
        
        return view('settings.index', compact('user', 'userType'));
    }

    /**
     * تحديث المعلومات الشخصية
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->withInput()
                ->with('activeTab', 'profile');
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // تحديث الصورة الشخصية إذا تم رفعها
        if ($request->hasFile('image')) {
            $this->deleteOldImage($user);
            
            $image = $request->file('image');
            $imageName = 'user_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }

        $user->update($data);

        return redirect()->route('settings.index')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح')
            ->with('activeTab', 'profile');
    }

    /**
     * تحديث كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('settings.index')
                ->withErrors($validator)
                ->with('activeTab', 'password');
        }

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->route('settings.index')
                ->with('error', 'كلمة المرور الحالية غير صحيحة')
                ->with('activeTab', 'password');
        }

        // تحديث كلمة المرور
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('settings.index')
            ->with('success', 'تم تغيير كلمة المرور بنجاح')
            ->with('activeTab', 'password');
    }

    /**
     * حذف الصورة القديمة
     */
    private function deleteOldImage($user)
    {
        if ($user->image && $user->image !== 'avatar.png') {
            $oldImagePath = public_path('images/' . $user->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
    }

    /**
     * تحديد نوع المستخدم بناءً على role_id
     */
    private function getUserType($user)
    {
        $role = $user->role;
        
        if (!$role) {
            return 'unknown';
        }

        switch ($role->role_name) {
            case 'Doctor':
                return 'teacher';
            case 'Head':
                return 'head_of_department';
            case 'Dean':
                return 'dean';
            default:
                return 'unknown';
        }
    }
}