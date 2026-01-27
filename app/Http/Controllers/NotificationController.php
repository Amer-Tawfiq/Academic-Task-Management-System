<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // عرض جميع الإشعارات (للمسؤولين)
    public function index()
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // إذا كان المستخدم معلمًا، نعرض إشعاراته فقط
        if ($roleName === 'teacher') {
            $query = Notification::where('user_id', $user->id)->latest();
        } else {
            // للمسؤولين: عرض جميع الإشعارات
            $query = Notification::with('user')->latest();
        }
        
        // تطبيق الفلاتر
        $query = $this->applyFilters($query);
        
        $notifications = $query->get();
        
        // جلب المستخدمين فقط للمسؤولين (لإرسال إشعارات)
        if ($roleName !== 'teacher') {
            $users = User::all();
            return view('notifications.index', compact('notifications', 'users'));
        }
        
        return view('teacher.notifications.index', compact('notifications'));
    }
    
    public function store(Request $request)
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // التحقق من أن المستخدم لديه صلاحية إرسال إشعارات
        if ($roleName === 'teacher') {
            return back()->with('error', 'ليس لديك صلاحية إرسال إشعارات');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ]);
        
        Notification::create($request->all());
        
        return redirect()->route('notifications.index')
            ->with('success', 'تم إرسال الإشعار بنجاح');
    }
    
    public function markAsRead(Notification $notification)
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // التحقق من أن الإشعار يخص المستخدم الحالي
        if ($roleName === 'teacher' && $notification->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح'
            ], 403);
        }
        
        $notification->update([
            'read_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'تم تعيين الإشعار كمقروء'
        ]);
    }
    
    public function destroy(Notification $notification)
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // التحقق من أن الإشعار يخص المستخدم الحالي
        if ($roleName === 'teacher' && $notification->user_id !== $user->id) {
            return back()->with('error', 'غير مصرح');
        }
        
        $notification->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'تم حذف الإشعار بنجاح');
    }
    
    public function stats()
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // إذا كان المستخدم معلمًا، نعرض إحصائيات إشعاراته فقط
        if ($roleName === 'teacher') {
            $stats = [
                'total' => Notification::where('user_id', $user->id)->count(),
                'unread' => Notification::where('user_id', $user->id)->whereNull('read_at')->count(),
                'read' => Notification::where('user_id', $user->id)->whereNotNull('read_at')->count(),
                'today' => Notification::where('user_id', $user->id)
                    ->whereDate('created_at', today())
                    ->count()
            ];
        } else {
            // للمسؤولين: إحصائيات جميع الإشعارات
            $stats = [
                'total' => Notification::count(),
                'unread' => Notification::whereNull('read_at')->count(),
                'read' => Notification::whereNotNull('read_at')->count(),
                'today' => Notification::whereDate('created_at', today())->count()
            ];
        }
        
        return response()->json($stats);
    }
    
    // دالة خاصة بعرض إشعارات المعلم فقط
    public function teacherNotifications()
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // التحقق من أن المستخدم معلم
        if ($roleName !== 'teacher') {
            abort(403, 'غير مصرح');
        }
        
        $query = Notification::where('user_id', $user->id)->latest();
        $query = $this->applyFilters($query);
        
        $notifications = $query->get();
        
        return view('teacher.notifications.index', compact('notifications'));
    }
    
    // دالة خاصة بعرض إشعارات رئيس القسم
    public function headNotifications()
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // التحقق من أن المستخدم رئيس قسم
        if ($roleName !== 'head_of_department') {
            abort(403, 'غير مصرح');
        }
        
        // يمكن إضافة منطق خاص لرؤساء الأقسام إذا كان لديهم صلاحيات مختلفة
        $query = Notification::where('user_id', $user->id)->latest();
        $query = $this->applyFilters($query);
        
        $notifications = $query->get();
        
        return view('head.notifications.index', compact('notifications'));
    }
    
    // دالة خاصة بعرض إشعارات العميد
    public function deanNotifications()
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // التحقق من أن المستخدم عميد
        if ($roleName !== 'dean') {
            abort(403, 'غير مصرح');
        }
        
        // يمكن إضافة منطق خاص للعمداء إذا كان لديهم صلاحيات مختلفة
        $query = Notification::where('user_id', $user->id)->latest();
        $query = $this->applyFilters($query);
        
        $notifications = $query->get();
        
        return view('dean.notifications.index', compact('notifications'));
    }
    
    // دالة مساعدة لتطبيق الفلاتر
    private function applyFilters($query)
    {
        // فلترة حسب الحالة
        if (request('status') == 'unread') {
            $query->whereNull('read_at');
        } elseif (request('status') == 'read') {
            $query->whereNotNull('read_at');
        }
        
        // فلترة حسب الفترة
        if (request('period') == 'today') {
            $query->whereDate('created_at', today());
        } elseif (request('period') == 'week') {
            $query->where('created_at', '>=', now()->subWeek());
        } elseif (request('period') == 'month') {
            $query->where('created_at', '>=', now()->subMonth());
        }
        
        // الترتيب
        if (request('sort') == 'oldest') {
            $query->oldest();
        }
        
        return $query;
    }
    
    // دالة مساعدة للحصول على اسم الدور
    private function getRoleName($user)
    {
        if (!$user->role) {
            return 'unknown';
        }
        
        switch ($user->role->role_name) {
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
    
    // دالة لعرض تفاصيل إشعار معين
    public function show(Notification $notification)
    {
        $user = Auth::user();
        $roleName = $this->getRoleName($user);
        
        // التحقق من أن الإشعار يخص المستخدم الحالي
        if ($roleName === 'teacher' && $notification->user_id !== $user->id) {
            abort(403, 'غير مصرح');
        }
        
        // تحديث حالة القراءة إذا كانت غير مقروءة
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }
        
        // عرض صفحة مختلفة حسب الدور
        switch ($roleName) {
            case 'teacher':
                return view('teacher.notifications.show', compact('notification'));
            case 'head_of_department':
                return view('head.notifications.show', compact('notification'));
            case 'dean':
                return view('dean.notifications.show', compact('notification'));
            default:
                return view('notifications.show', compact('notification'));
        }
    }
}