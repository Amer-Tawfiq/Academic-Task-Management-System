<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    public function index()
    {
        $query = Notification::with('user')->latest();
        
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
        
        $notifications = $query->get();
        $users = User::all();
        
        return view('notifications.index', compact('notifications', 'users'));
    }

    public function store(Request $request)
    {
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
        $notification->delete();
        
        return redirect()->route('notifications.index')
            ->with('success', 'تم حذف الإشعار بنجاح');
    }

    public function stats()
    {
        return response()->json([
            'total' => Notification::count(),
            'unread' => Notification::whereNull('read_at')->count(),
            'read' => Notification::whereNotNull('read_at')->count(),
            'today' => Notification::whereDate('created_at', today())->count()
        ]);
    }
}