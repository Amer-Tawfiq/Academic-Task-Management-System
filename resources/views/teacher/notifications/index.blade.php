@extends('layouts.teacher')

@section('content')
<style>
    /* تصميم مبسط وأنيق */
    .notifications-container {
        padding: 30px;
        font-family: 'Cairo', sans-serif;
        background: #f8fafc;
        min-height: calc(100vh - 60px);
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        color: #1e293b;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        display: inline-block;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -5px;
        right: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(to left, #3b82f6, #1d4ed8);
        border-radius: 2px;
    }

    .page-subtitle {
        font-size: 16px;
        color: #64748b;
        margin-top: 5px;
    }

    /* الإحصائيات */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        display: block;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        margin-top: 2px;
    }

    /* قائمة الإشعارات */
    .notifications-list {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .notification-item {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        transition: background-color 0.2s ease;
        position: relative;
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-item:hover {
        background-color: #f8fafc;
    }

    .notification-item.unread {
        background-color: #f0f9ff;
        border-right: 3px solid #3b82f6;
    }

    .notification-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
        margin-top: 5px;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .notification-message {
        font-size: 14px;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 10px;
        max-height: 60px;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .notification-time {
        font-size: 13px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .notification-time i {
        font-size: 12px;
    }

    .notification-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        font-family: 'Cairo', sans-serif;
        font-weight: 500;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }

    .btn-danger {
        background: #ef4444;
        color: white;
    }

    .btn-danger:hover {
        background: #dc2626;
        transform: translateY(-1px);
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 12px;
    }

    /* الحالة الفارغة */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state-icon {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 15px;
        opacity: 0.6;
    }

    .empty-state-text {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 20px;
    }

    /* البادجات */
    .read-badge, .unread-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .read-badge {
        background: #d1fae5;
        color: #065f46;
    }

    .unread-badge {
        background: #fee2e2;
        color: #991b1b;
    }

    /* تصميم متجاوب */
    @media (max-width: 768px) {
        .notifications-container {
            padding: 20px;
        }
        
        .notification-item {
            flex-direction: column;
            gap: 12px;
        }
        
        .notification-actions {
            align-self: flex-end;
            margin-top: 10px;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="notifications-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1 class="page-title">إشعاراتي</h1>
        <div class="page-subtitle">عرض الإشعارات المرسلة إليك</div>
    </div>

    <!-- إحصائيات -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon" style="background: #e0f2fe; color: #0369a1;">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $notifications->count() }}</span>
                <span class="stat-label">إجمالي الإشعارات</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $notifications->whereNull('read_at')->count() }}</span>
                <span class="stat-label">غير مقروءة</span>
            </div>
        </div>
    </div>

    <!-- قائمة الإشعارات -->
    <div class="notifications-list">
        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
            <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" 
                 onclick="markAsRead({{ $notification->id }})" style="cursor: pointer;">
                <div class="notification-icon" 
                     style="background: {{ $notification->read_at ? '#f1f5f9' : '#dbeafe' }}; 
                            color: {{ $notification->read_at ? '#94a3b8' : '#1d4ed8' }};">
                    <i class="fas {{ $notification->read_at ? 'fa-envelope-open' : 'fa-envelope' }}"></i>
                </div>
                
                <div class="notification-content">
                    <div class="notification-title">
                        {{ $notification->title }}
                        <span class="{{ $notification->read_at ? 'read-badge' : 'unread-badge' }}">
                            {{ $notification->read_at ? 'مقروء' : 'غير مقروء' }}
                        </span>
                    </div>
                    
                    <div class="notification-message">
                        {{ $notification->message }}
                    </div>
                    
                    <div class="notification-time">
                        <i class="fas fa-clock"></i>
                        {{ $notification->created_at->diffForHumans() }}
                        •
                        <i class="fas fa-calendar-alt"></i>
                        {{ $notification->created_at->format('Y/m/d') }}
                    </div>
                </div>
                
                <div class="notification-actions">
                    @if(!$notification->read_at)
                        <button onclick="markAsRead({{ $notification->id }})" class="btn btn-primary btn-sm">
                            <i class="fas fa-check"></i>
                            تعيين كمقروء
                        </button>
                    @endif
                    
                    <button onclick="deleteNotification({{ $notification->id }})" class="btn btn-danger btn-sm">
                        <i class="fas fa-trash"></i>
                        حذف
                    </button>
                </div>
            </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <div class="empty-state-text">
                    لا توجد إشعارات حالياً
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    function markAsRead(notificationId) {
        fetch(`/teacher/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
    
    function deleteNotification(notificationId) {
        if (confirm('هل أنت متأكد من حذف هذا الإشعار؟')) {
            fetch(`/teacher/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }
    
    // تفعيل النقر على الإشعار
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            if (!e.target.closest('.notification-actions')) {
                const notificationId = this.getAttribute('onclick')?.match(/\d+/)?.[0];
                if (notificationId) {
                    markAsRead(notificationId);
                }
            }
        });
    });
</script>
@endsection