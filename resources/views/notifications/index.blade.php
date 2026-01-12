@extends('layouts.dashboard')

@section('content')
<style>
    :root {
        --primary-color: #3b82f6;
        --primary-light: #eff6ff;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --info-color: #0ea5e9;
        --dark-color: #1e293b;
        --light-color: #f8fafc;
        --gray-color: #94a3b8;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    .notifications-container {
        padding: 30px;
        font-family: 'Cairo', sans-serif;
        background: linear-gradient(135deg, #f6f8fc 0%, #eef2ff 100%);
        min-height: calc(100vh - 60px);
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .page-title {
        color: #1e293b;
        font-size: 28px;
        font-weight: 700;
        position: relative;
        padding-right: 15px;
    }

    .page-title::before {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 5px;
        background: linear-gradient(180deg, var(--primary-color), #1d4ed8);
        border-radius: 10px;
    }

    .page-subtitle {
        font-size: 16px;
        color: #64748b;
        margin-top: 5px;
        font-weight: 500;
    }

    /* إحصائيات سريعة */
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .stat-card.active {
        background: linear-gradient(135deg, var(--primary-light), #e0f2fe);
        border-color: var(--primary-color);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark-color);
        display: block;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 14px;
        color: #64748b;
        margin-top: 2px;
    }

    /* الفلاتر */
    .filters-container {
        background: white;
        padding: 20px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        margin-bottom: 25px;
        border: 1px solid var(--border-color);
    }

    .filter-form {
        display: flex;
        align-items: flex-end;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 200px;
    }

    .filter-label {
        color: var(--dark-color);
        font-weight: 600;
        font-size: 14px;
    }

    .filter-select {
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        background: white;
        color: var(--dark-color);
        font-family: 'Cairo', sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
    }

    .filter-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* الأزرار */
    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: var(--radius-sm);
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning-color), #d97706);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        min-width: 100px;
    }

    /* قائمة الإشعارات */
    .notifications-list {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .notification-item {
        padding: 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        align-items: flex-start;
        gap: 15px;
        transition: all 0.3s ease;
        position: relative;
    }

    .notification-item:hover {
        background: var(--primary-light);
    }

    .notification-item.unread {
        background: #f8fafc;
        border-right: 4px solid var(--primary-color);
    }

    .notification-item:last-child {
        border-bottom: none;
    }

    .notification-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
    }

    .notification-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 5px;
    }

    .notification-message {
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .notification-time {
        font-size: 12px;
        color: var(--gray-color);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .notification-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    .read-badge {
        background: var(--primary-color);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .unread-badge {
        background: var(--danger-color);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    /* حالة فارغة */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: var(--gray-color);
    }

    .empty-state-icon {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    .empty-state-text {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 20px;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        animation: fadeIn 0.3s ease;
        overflow-y: auto;
        padding: 20px 0;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        width: 90%;
        max-width: 600px;
        margin: 20px auto;
        padding: 30px;
        border-radius: var(--radius-lg);
        box-shadow: var(--shadow-lg);
        animation: slideIn 0.3s ease;
        position: relative;
        max-height: calc(100vh - 40px);
        display: flex;
        flex-direction: column;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
        flex-shrink: 0;
    }

    .modal-title {
        color: var(--dark-color);
        font-size: 22px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-title i {
        color: var(--primary-color);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--gray-color);
        cursor: pointer;
        transition: color 0.3s ease;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .modal-close:hover {
        color: var(--danger-color);
        background: #fee2e2;
    }

    .modal-body {
        flex: 1;
        overflow-y: auto;
        padding-right: 5px;
        margin-bottom: 20px;
    }

    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* نموذج الإضافة */
    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: var(--dark-color);
        font-weight: 600;
        font-size: 14px;
    }

    .form-label.required::after {
        content: ' *';
        color: var(--danger-color);
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-family: 'Cairo', sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        flex-shrink: 0;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .notifications-container {
            padding: 15px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .notification-item {
            flex-direction: column;
            gap: 10px;
        }
        
        .notification-actions {
            align-self: flex-end;
        }
        
        .modal-content {
            width: 95%;
            margin: 10px auto;
            padding: 20px;
            max-height: calc(100vh - 20px);
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions button {
            width: 100%;
        }
    }
</style>

<div class="notifications-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1 class="page-title">الإشعارات</h1>
            <div class="page-subtitle">إدارة وتتبع جميع الإشعارات الخاصة بك</div>
        </div>
        <button class="btn btn-success" onclick="showAddNotificationModal()">
            <i class="fas fa-bell"></i>
            إرسال إشعار جديد
        </button>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="stats-container">
        <div class="stat-card active" onclick="filterNotifications('all')">
            <div class="stat-icon" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1;">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $notifications->count() }}</span>
                <span class="stat-label">إجمالي الإشعارات</span>
            </div>
        </div>
        
        <div class="stat-card" onclick="filterNotifications('unread')">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b;">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $notifications->whereNull('read_at')->count() }}</span>
                <span class="stat-label">غير مقروءة</span>
            </div>
        </div>
        
        <div class="stat-card" onclick="filterNotifications('read')">
            <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46;">
                <i class="fas fa-envelope-open"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $notifications->whereNotNull('read_at')->count() }}</span>
                <span class="stat-label">مقروءة</span>
            </div>
        </div>
        
        <div class="stat-card" onclick="filterNotifications('today')">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e;">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $notifications->where('created_at', '>=', now()->startOfDay())->count() }}</span>
                <span class="stat-label">اليوم</span>
            </div>
        </div>
    </div>

    <!-- الفلاتر -->
    <div class="filters-container">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label class="filter-label">حالة الإشعار</label>
                <select name="status" class="filter-select">
                    <option value="">جميع الحالات</option>
                    <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>غير مقروء</option>
                    <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>مقروء</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">الفترة الزمنية</label>
                <select name="period" class="filter-select">
                    <option value="">جميع الفترات</option>
                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>اليوم</option>
                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>آخر أسبوع</option>
                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>آخر شهر</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">ترتيب حسب</label>
                <select name="sort" class="filter-select">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث أولاً</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i>
                تطبيق الفلتر
            </button>
            
            @if(request()->hasAny(['status', 'period', 'sort']))
                <a href="{{ route('notifications.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>
                    إلغاء الفلتر
                </a>
            @endif
        </form>
    </div>

    <!-- قائمة الإشعارات -->
    <div class="notifications-list">
        @if($notifications->count() > 0)
            @foreach($notifications as $notification)
            <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" 
                 onclick="markAsRead({{ $notification->id }})">
                <div class="notification-icon" 
                     style="background: {{ $notification->read_at ? '#e2e8f0' : '#e0f2fe' }}; 
                            color: {{ $notification->read_at ? '#94a3b8' : '#0369a1' }};">
                    <i class="fas {{ $notification->read_at ? 'fa-envelope-open' : 'fa-envelope' }}"></i>
                </div>
                
                <div class="notification-content">
                    <div class="notification-title">
                        {{ $notification->title }}
                        <span class="{{ $notification->read_at ? 'read-badge' : 'unread-badge' }}" 
                              style="font-size: 11px; margin-right: 8px;">
                            {{ $notification->read_at ? 'مقروء' : 'غير مقروء' }}
                        </span>
                    </div>
                    
                    <div class="notification-message">
                        {{ Str::limit($notification->message, 200) }}
                    </div>
                    
                    <div class="notification-time">
                        <i class="fas fa-clock"></i>
                        {{ $notification->created_at->diffForHumans() }}
                        • 
                        <i class="fas fa-calendar-alt"></i>
                        {{ $notification->created_at->format('d/m/Y') }}
                    </div>
                </div>
                
                <div class="notification-actions">
                    @if(!$notification->read_at)
                        <form method="POST" action="{{ route('notifications.markAsRead', $notification) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-check"></i>
                                تعيين كمقروء
                            </button>
                        </form>
                    @endif
                    
                    <form method="POST" action="{{ route('notifications.destroy', $notification) }}" style="display: inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="event.stopPropagation(); return confirm('هل أنت متأكد من حذف هذا الإشعار؟')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
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
                <button class="btn btn-success" onclick="showAddNotificationModal()">
                    <i class="fas fa-bell"></i>
                    إنشاء أول إشعار
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Modal لإضافة إشعار جديد -->
<div id="addNotificationModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-bell"></i>
                إرسال إشعار جديد
            </h3>
            <button class="modal-close" onclick="hideAddNotificationModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('notifications.store') }}" id="addNotificationForm">
                @csrf
                <div class="form-group">
                    <label class="form-label required">عنوان الإشعار</label>
                    <input type="text" name="title" placeholder="أدخل عنوان الإشعار" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">رسالة الإشعار</label>
                    <textarea name="message" placeholder="أدخل رسالة الإشعار" class="form-input" rows="6" required></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">المستخدم المستهدف</label>
                    <select name="user_id" class="form-input" required>
                        <option value="">اختر المستخدم</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} 
                                @if($user->role)
                                    - {{ $user->role->role_name }}
                                @endif
                                @if($user->department)
                                    ({{ $user->department->name }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-cancel" onclick="hideAddNotificationModal()">
                <i class="fas fa-times"></i>
                إلغاء
            </button>
            <button type="submit" form="addNotificationForm" class="btn btn-success">
                <i class="fas fa-paper-plane"></i>
                إرسال الإشعار
            </button>
        </div>
    </div>
</div>

<script>
    // إدارة الـ Modals
    function showAddNotificationModal() {
        const modal = document.getElementById('addNotificationModal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function hideAddNotificationModal() {
        const modal = document.getElementById('addNotificationModal');
        modal.style.display = 'none';
        document.getElementById('addNotificationForm').reset();
        document.body.style.overflow = 'auto';
    }

    // تحديد الإشعار كمقروء عند النقر عليه
    function markAsRead(notificationId) {
        const notificationItem = document.querySelector(`[onclick*="${notificationId}"]`);
        
        // إذا كان الإشعار غير مقروء
        if (notificationItem.classList.contains('unread')) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // تحديث الواجهة دون إعادة تحميل الصفحة
                    notificationItem.classList.remove('unread');
                    
                    const icon = notificationItem.querySelector('.notification-icon i');
                    icon.classList.remove('fa-envelope');
                    icon.classList.add('fa-envelope-open');
                    
                    const badge = notificationItem.querySelector('.unread-badge');
                    if (badge) {
                        badge.className = 'read-badge';
                        badge.textContent = 'مقروء';
                        badge.style.fontSize = '11px';
                        badge.style.marginRight = '8px';
                    }
                    
                    const actionBtn = notificationItem.querySelector('.notification-actions form button');
                    if (actionBtn) {
                        actionBtn.parentElement.remove();
                    }
                    
                    // تحديث الإحصائيات
                    updateStats();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }

    // تحديث الإحصائيات
    function updateStats() {
        fetch('/notifications/stats')
            .then(response => response.json())
            .then(data => {
                document.querySelector('.stat-card:nth-child(1) .stat-number').textContent = data.total;
                document.querySelector('.stat-card:nth-child(2) .stat-number').textContent = data.unread;
                document.querySelector('.stat-card:nth-child(3) .stat-number').textContent = data.read;
                document.querySelector('.stat-card:nth-child(4) .stat-number').textContent = data.today;
            });
    }

    // فلترة الإشعارات
    function filterNotifications(type) {
        let url = new URL(window.location.href);
        
        switch(type) {
            case 'all':
                url.searchParams.delete('status');
                url.searchParams.delete('period');
                break;
            case 'unread':
                url.searchParams.set('status', 'unread');
                break;
            case 'read':
                url.searchParams.set('status', 'read');
                break;
            case 'today':
                url.searchParams.set('period', 'today');
                break;
        }
        
        window.location.href = url.toString();
    }

    // إغلاق النوافذ بالضغط خارجها
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            hideAddNotificationModal();
        }
    }

    // إغلاق النوافذ بالضغط على ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideAddNotificationModal();
        }
    });

    // التأكد من إغلاق الـ Modal عند إرسال النموذج
    document.getElementById('addNotificationForm')?.addEventListener('submit', function(e) {
        setTimeout(() => {
            hideAddNotificationModal();
        }, 100);
    });
</script>
@endsection