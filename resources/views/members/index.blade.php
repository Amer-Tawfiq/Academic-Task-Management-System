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

    .teachers-container {
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

    /* شبكة عرض المدرسين */
    .teachers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .teacher-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .teacher-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .teacher-header {
        background: linear-gradient(135deg, var(--primary-light), #e0f2fe);
        padding: 20px;
        text-align: center;
        position: relative;
    }

    .teacher-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid white;
        margin: 0 auto 15px;
        overflow: hidden;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: var(--primary-color);
    }

    .teacher-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .teacher-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 5px;
    }

    .teacher-title {
        font-size: 14px;
        color: var(--primary-color);
        font-weight: 600;
        background: white;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    .teacher-body {
        padding: 20px;
    }

    .teacher-info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        color: #64748b;
        font-size: 14px;
    }

    .teacher-info-item i {
        width: 20px;
        color: var(--primary-color);
    }

    .teacher-footer {
        padding: 15px 20px;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    /* حالة فارغة */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: var(--gray-color);
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .teachers-container {
            padding: 15px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .stats-container {
            grid-template-columns: 1fr;
        }
        
        .teachers-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            min-width: 100%;
        }
    }
</style>

<div class="teachers-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1 class="page-title">المدرسون</h1>
            <div class="page-subtitle">إدارة وتتبع أعضاء هيئة التدريس</div>
        </div>
        <a href="{{ route('members.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus"></i>
            إضافة مدرس جديد
        </a>
    </div>

    <!-- رسائل التنبيه -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- إحصائيات سريعة -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #e0f2fe, #bae6fd); color: #0369a1;">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $teachers->count() }}</span>
                <span class="stat-label">إجمالي المدرسين</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $teachers->count() }}</span>
                <span class="stat-label">مدرسين نشطين</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                @php
                    $totalCourses = 0;
                    foreach($teachers as $teacher) {
                        $totalCourses += \App\Models\Course::where('teacher_id', $teacher->id)->count();
                    }
                @endphp
                <span class="stat-number">{{ $totalCourses }}</span>
                <span class="stat-label">مقررات مُدرَّسة</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f3e8ff, #e9d5ff); color: #7c3aed;">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $departments->count() }}</span>
                <span class="stat-label">أقسام أكاديمية</span>
            </div>
        </div>
    </div>

    <!-- الفلاتر -->
    <div class="filters-container">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label class="filter-label">القسم الأكاديمي</label>
                <select name="department_id" class="filter-select">
                    <option value="">جميع الأقسام</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">ترتيب حسب</label>
                <select name="sort" class="filter-select">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم (أ-ي)</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>الأقدم</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i>
                تطبيق الفلتر
            </button>
        </form>
    </div>

    <!-- شبكة عرض المدرسين -->
    @if($teachers->count() > 0)
        <div class="teachers-grid">
            @foreach($teachers as $teacher)
            <div class="teacher-card">
                <div class="teacher-header">
                    <div class="teacher-avatar">
                        @if($teacher->image)
                            <img src="{{ asset('images/' . $teacher->image) }}" alt="{{ $teacher->name }}" onerror="this.src='{{ asset('images/avatar.png') }}'">
                        @else
                            <i class="fas fa-user-tie"></i>
                        @endif
                    </div>
                    
                    <div class="teacher-name">{{ $teacher->name }}</div>
                    <span class="teacher-title">مدرس</span>
                </div>
                
                <div class="teacher-body">
                    <div class="teacher-info-item">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $teacher->email }}</span>
                    </div>
                    
                    <div class="teacher-info-item">
                        <i class="fas fa-building"></i>
                        <span>{{ $teacher->department->name ?? 'غير محدد' }}</span>
                    </div>
                    
                    @php
                        $coursesCount = \App\Models\Course::where('teacher_id', $teacher->id)->count();
                    @endphp
                    
                    @if($coursesCount > 0)
                        <div class="teacher-info-item">
                            <i class="fas fa-book"></i>
                            <span>{{ $coursesCount }} مقرر</span>
                        </div>
                    @endif
                    
                    <div class="teacher-info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>انضم في {{ $teacher->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
                
                <div class="teacher-footer">
                    <a href="{{ route('members.show', $teacher) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-eye"></i> عرض
                    </a>
                    
                    <a href="{{ route('members.edit', $teacher) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                    
                    <form method="POST" action="{{ route('members.destroy', $teacher) }}" style="display: inline;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" 
                                onclick="return confirm('هل أنت متأكد من حذف هذا المدرس؟')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <div class="empty-state-text">
                لا يوجد مدرسون مسجلون حالياً
            </div>
            <a href="{{ route('members.create') }}" class="btn btn-success">
                <i class="fas fa-user-plus"></i>
                إضافة أول مدرس
            </a>
        </div>
    @endif
</div>
@endsection