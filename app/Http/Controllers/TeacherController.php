<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Department;
use App\Models\Role;
use App\Models\Course;

class TeacherController extends Controller
{
    // ===== الوظائف للصفحة الشخصية للمعلم =====
    public function profile()
    {
        $user = Auth::user();
        return view('teacher.profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('teacher.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        if ($request->hasFile('image')) {
            if ($user->image) {
                $oldImagePath = public_path('images/' . $user->image);
                if (file_exists($oldImagePath) && $user->image !== 'avatar.png') {
                    unlink($oldImagePath);
                }
            }
            
            $image = $request->file('image');
            $imageName = 'user_' . $user->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }
        
        $user->update($data);
        
        return redirect()->route('teacher.profile')
            ->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    // ===== الوظائف لإدارة المدرسين (الأعضاء) =====
    public function index()
    {
        try {
            // جلب role_id للمعلم باستخدام role_name بدلاً من name
            $teacherRole = Role::where('role_name', 'Doctor')->first();
            
            if (!$teacherRole) {
                // إذا لم يكن هناك دور "معلم"، قم بإنشائه
                $teacherRole = Role::create([
                    'role_name' => 'Doctor'
                ]);
            }
            
            // جلب فقط المستخدمين ذوي role_id = معلم
            $teachers = User::with(['department', 'courses'])
                ->where('role_id', $teacherRole->id)
                ->orderBy('name')
                ->get();
            
            $departments = Department::all();
            
            // حساب عدد المقررات الإجمالي
            $totalCourses = Course::count();
            
            return view('members.index', compact('teachers', 'departments', 'teacherRole', 'totalCourses'));
            
        } catch (\Exception $e) {
            // في حالة حدوث خطأ، قم بإرجاع رسالة خطأ
            return back()->with('error', 'حدث خطأ في جلب بيانات المدرسين: ' . $e->getMessage());
        }
    }

    public function create()
    {
        $departments = Department::all();
        $teacherRole = Role::where('role_name', 'Doctor')->first();
        
        if (!$teacherRole) {
            // إذا لم يكن هناك دور "معلم"، قم بإنشائه
            $teacherRole = Role::create([
                'role_name' => 'Doctor'
            ]);
        }
        
        return view('members.create', compact('departments', 'teacherRole'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'required|exists:departments,id',
            'role_id' => 'required|exists:roles,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only(['name', 'email', 'department_id', 'role_id']);
        $data['password'] = Hash::make($request->password);
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = 'user_' . time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['image'] = $imageName;
        }
        
        User::create($data);
        
        return redirect()->route('members.index')
            ->with('success', 'تم إضافة المدرس بنجاح');
    }

    public function show($id)
    {
        try {
            $teacher = User::with(['department', 'courses'])
                ->findOrFail($id);
            
            return view('members.show', compact('teacher'));
            
        } catch (\Exception $e) {
            return redirect()->route('members.index')
                ->with('error', 'لم يتم العثور على المدرس المطلوب');
        }
    }

   public function editMember($id)
    {
        try {
            $teacher = User::findOrFail($id);
            $departments = Department::all();
            
            return view('members.edit', compact('teacher', 'departments'));
            
        } catch (\Exception $e) {
            return redirect()->route('members.index')
                ->with('error', 'لم يتم العثور على المدرس المطلوب');
        }
    }

    public function updateMember(Request $request, $id)
    {
        try {
            $teacher = User::findOrFail($id);
            
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $teacher->id,
                'department_id' => 'required|exists:departments,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'nullable|string|min:8|confirmed',
            ]);
            
            $data = $request->only(['name', 'email', 'department_id']);
            
            // تحديث كلمة المرور إذا تم إدخالها
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }
            
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($teacher->image && $teacher->image !== 'avatar.png') {
                    $oldImagePath = public_path('images/' . $teacher->image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                // حفظ الصورة الجديدة
                $image = $request->file('image');
                $imageName = 'user_' . $teacher->id . '_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);
                $data['image'] = $imageName;
            }
            
            $teacher->update($data);
            
            return redirect()->route('members.index')
                ->with('success', 'تم تحديث بيانات المدرس بنجاح');
                
        } catch (\Exception $e) {
            return redirect()->route('members.index')
                ->with('error', 'حدث خطأ أثناء تحديث بيانات المدرس');
        }
    }

    public function destroy($id)
    {
        try {
            $teacher = User::findOrFail($id);
            
            // حذف الصورة إذا كانت موجودة
            if ($teacher->image && $teacher->image !== 'avatar.png') {
                $imagePath = public_path('images/' . $teacher->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $teacher->delete();
            
            return redirect()->route('members.index')
                ->with('success', 'تم حذف المدرس بنجاح');
                
        } catch (\Exception $e) {
            return redirect()->route('members.index')
                ->with('error', 'حدث خطأ أثناء حذف المدرس');
        }
    }

    // ===== وظائف تغيير كلمة المرور =====
    public function showChangePasswordForm()
    {
        $user = Auth::user();
        return view('teacher.change-password', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'كلمة المرور الحالية غير صحيحة');
        }
        
        // تحديث كلمة المرور
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        
        return redirect()->route('teacher.profile')
            ->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}