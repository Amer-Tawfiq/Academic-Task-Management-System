@php
    // تحديد الـ Layout بناءً على دور المستخدم
    $userRole = auth()->user()->role->role_name;
    $layout = $userRole == 'Dean' ? 'layouts.dean' : 'layouts.dashboard';
@endphp

@extends($layout)

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

    .tasks-container {
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

    /* رسائل التنبيه */
    .alert {
        padding: 15px 20px;
        border-radius: var(--radius-sm);
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #ecfdf5);
        color: #065f46;
        border-right: 4px solid var(--success-color);
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border-right: 4px solid var(--warning-color);
    }

    .alert-info {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
        border-right: 4px solid var(--info-color);
    }

    .alert-success i,
    .alert-warning i,
    .alert-info i {
        font-size: 18px;
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
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
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

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-warning {
        background: linear-gradient(135deg, var(--warning-color), #d97706);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: white;
    }

    .btn-info {
        background: linear-gradient(135deg, var(--info-color), #0284c7);
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
        min-width: 100px;
    }
    .btn-icon{
    min-width:auto;
    padding:6px 10px;
}


    /* جدول المهام */
    .tasks-table-container {
        background: white;
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .tasks-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .tasks-table thead {
        background: linear-gradient(90deg, var(--primary-light), #e0f2fe);
    }

    .tasks-table th {
        padding: 18px 15px;
        text-align: right;
        color: var(--dark-color);
        font-weight: 700;
        font-size: 15px;
        border-bottom: 2px solid var(--primary-color);
    }

    .tasks-table td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
        transition: background 0.2s ease;
    }

    .tasks-table tbody tr:hover {
        background: var(--primary-light);
    }

    .tasks-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* حالات المهام */
    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        display: inline-block;
        min-width: 100px;
    }

    .status-pending {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }

    .status-review {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
    }

    .status-late {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .status-completed {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
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

    /* تاريخ التسليم */
    .date-input-container {
        position: relative;
    }

    .date-input-container i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-color);
    }

    .date-input-container input {
        padding-right: 15px;
        padding-left: 45px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .tasks-container {
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
        
        .tasks-table {
            display: block;
            overflow-x: auto;
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

    @media (max-width: 480px) {
        .tasks-table td, .tasks-table th {
            padding: 10px;
            font-size: 13px;
        }
        
        .status-badge {
            min-width: 80px;
            padding: 4px 12px;
            font-size: 12px;
        }
    }

    /* تأثيرات إضافية */
    .task-row {
        transition: all 0.3s ease;
    }

    .task-row:hover {
        transform: translateX(-5px);
    }

    /* أيقونة التقويم */
    .fa-calendar {
        color: var(--primary-color);
    }

    /* زر الإجراءات */
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap:nowrap;
        white-space:nowrap;
    }

    /* مؤشر التقدم */
    .progress-indicator {
        font-size: 11px;
        color: #64748b;
        margin-top: 5px;
    }
</style>

<div class="tasks-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1 class="page-title">مهام الطلاب</h1>
            <div class="page-subtitle">إدارة المهام وتتبع إنجاز الطلاب</div>
        </div>
        <button class="btn btn-success" onclick="showAddTaskModal()">
            <i class="fas fa-plus"></i>
            إضافة مهمة جديدة
        </button>
    </div>

    <!-- رسائل التنبيه -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('warning') }}
        </div>
    @endif

    <!-- إحصائيات سريعة -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1;">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $tasks->count() }}</span>
                <span class="stat-label">إجمالي المهام</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $tasks->where('status', 'completed')->count() }}</span>
                <span class="stat-label">مهام مكتملة</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $tasks->where('status', 'pending')->count() }}</span>
                <span class="stat-label">مهام معلقة</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fee2e2, #fecaca); color: #991b1b;">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $tasks->where('status', 'late')->count() }}</span>
                <span class="stat-label">مهام متأخرة</span>
            </div>
        </div>
    </div>

    <!-- الفلاتر -->
    <div class="filters-container">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label class="filter-label">ترتيب حسب المقرر</label>
                <select name="course_id" class="filter-select">
                    <option value="">جميع المقررات</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">ترتيب حسب الحالة</label>
                <select name="status" class="filter-select">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                    <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>قيد المراجعة</option>
                    <option value="late" {{ request('status') == 'late' ? 'selected' : '' }}>متأخرة</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">ترتيب حسب</label>
                <select name="sort" class="filter-select">
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث أولاً</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم أولاً</option>
                    <option value="due_date" {{ request('sort') == 'due_date' ? 'selected' : '' }}>تاريخ التسليم</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i>
                تطبيق الفلتر
            </button>
        </form>
    </div>

    <!-- جدول المهام -->
    <div class="tasks-table-container">
        @if($tasks->count() > 0)
            <table class="tasks-table">
                <thead>
                    <tr>
                        <th>عنوان المهمة</th>
                        <th>المقرر</th>
                        <th>تاريخ التسليم</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                    <tr class="task-row">
                        <td>
                            <div style="font-weight: 600; color: #1e293b; margin-bottom: 5px;">
                                {{ $task->title }}
                                @if($task->student_submissions_count > 0)
                                    <span style="font-size: 12px; background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 10px; margin-right: 8px;">
                                        {{ $task->student_submissions_count }} تسليم
                                    </span>
                                @endif
                            </div>
                            @if($task->description)
                                <div style="font-size: 13px; color: #64748b; margin-bottom: 5px;">
                                    {{ Str::limit($task->description, 80) }}
                                </div>
                            @endif
                           
                        </td>
                        <td>
                            <div style="font-weight: 500; margin-bottom: 3px;">
                                {{ $task->course->course_name }}
                            </div>
                            <div style="font-size: 12px; color: #64748b;">
                                {{ $task->course->course_code }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-calendar-alt" style="color: var(--primary-color);"></i>
                                <span>{{ \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') }}</span>
                            </div>
                            @if(\Carbon\Carbon::parse($task->due_date)->isPast() && $task->status != 'completed')
                                <div style="font-size: 11px; color: var(--danger-color); margin-top: 4px;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    تجاوز الموعد النهائي
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($task->status == 'pending')
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock"></i> معلقة
                                </span>
                            @elseif($task->status == 'review')
                                <span class="status-badge status-review">
                                    <i class="fas fa-search"></i> قيد المراجعة
                                </span>
                            @elseif($task->status == 'late')
                                <span class="status-badge status-late">
                                    <i class="fas fa-exclamation-circle"></i> متأخر
                                </span>
                            @elseif($task->status == 'completed')
                                <span class="status-badge status-completed">
                                    <i class="fas fa-check-circle"></i> مكتمل
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                
                                
                                <form method="POST" action="{{ route('tasks.status', $task) }}" style="display: inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                        تغيير الحالة
                                    </button>
                                </form>
                        
                                
                                <form method="POST" action="{{ route('tasks.destroy', $task) }}" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-icon" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="empty-state-text">
                    لا توجد مهام حالياً.
                </div>
                <button class="btn btn-success" onclick="showAddTaskModal()">
                    <i class="fas fa-plus"></i>
                    إضافة أول مهمة
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Modal لإضافة مهمة -->
<div id="addTaskModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-plus-circle"></i>
                إضافة مهمة جديدة
            </h3>
            <button class="modal-close" onclick="hideAddTaskModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('tasks.store') }}" id="addTaskForm">
                @csrf
                <div class="form-group">
                    <label class="form-label required">عنوان المهمة</label>
                    <input type="text" name="title" placeholder="أدخل عنوان المهمة" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">وصف المهمة (اختياري)</label>
                    <textarea name="description" placeholder="أدخل وصف المهمة" class="form-input" rows="4"></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">المقرر</label>
                    <select name="course_id" class="form-input" required>
                        <option value="">اختر المقرر</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label required">تاريخ التسليم</label>
                    <div class="date-input-container">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" name="due_date" class="form-input" required
                               min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">حالة المهمة الابتدائية</label>
                    <select name="status" class="form-input">
                        <option value="pending">معلقة</option>
                        <option value="review">قيد المراجعة</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-cancel" onclick="hideAddTaskModal()">
                <i class="fas fa-times"></i>
                إلغاء
            </button>
            <button type="submit" form="addTaskForm" class="btn btn-success">
                <i class="fas fa-save"></i>
                حفظ المهمة
            </button>
        </div>
    </div>
</div>

<!-- Modal لعرض التسليمات -->
<div id="submissionsModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-file-upload"></i>
                تسليمات المهمة
            </h3>
            <button class="modal-close" onclick="hideSubmissionsModal()">&times;</button>
        </div>
        <div class="modal-body" id="submissionsContent">
            <!-- سيتم تعبئة المحتوى عبر JavaScript -->
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-cancel" onclick="hideSubmissionsModal()">
                <i class="fas fa-times"></i>
                إغلاق
            </button>
        </div>
    </div>
</div>

<script>
    // إدارة الـ Modals
    function showAddTaskModal() {
        const modal = document.getElementById('addTaskModal');
        modal.style.display = 'block';
        // منع التمرير خلف الـ Modal
        document.body.style.overflow = 'hidden';
    }

    function hideAddTaskModal() {
        const modal = document.getElementById('addTaskModal');
        modal.style.display = 'none';
        document.getElementById('addTaskForm').reset();
        // إعادة التمرير للجسم
        document.body.style.overflow = 'auto';
    }

    function hideSubmissionsModal() {
        const modal = document.getElementById('submissionsModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // إغلاق النوافذ بالضغط خارجها
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            hideAddTaskModal();
            hideSubmissionsModal();
        }
    }

    // إغلاق النوافذ بالضغط على ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideAddTaskModal();
            hideSubmissionsModal();
        }
    });

    // التأكد من إغلاق الـ Modal عند إرسال النموذج
    document.getElementById('addTaskForm').addEventListener('submit', function(e) {
        // يمكنك إضافة التحقق الإضافي هنا إذا لزم الأمر
        setTimeout(() => {
            hideAddTaskModal();
        }, 100);
    });

    // تحديد التاريخ الحالي كحد أدنى
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="due_date"]');
        if (dateInput) {
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        }
    });

    // تحديث تلقائي للحالة بناءً على تاريخ التسليم
    function updateTaskStatus(taskId, newStatus) {
        fetch(`/tasks/${taskId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection