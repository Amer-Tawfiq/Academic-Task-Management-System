@extends('layouts.dashboard')

@section('content')
<style>
    :root {
        --primary-color: #3b82f6;
        --primary-light: #eff6ff;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --radius-sm: 8px;
        --radius-md: 12px;
    }

    .show-container {
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
        text-decoration: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-info {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: white;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: white;
    }

    .profile-card {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }

    .profile-header {
        background: linear-gradient(135deg, var(--primary-light), #e0f2fe);
        padding: 40px 30px;
        text-align: center;
    }

    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid white;
        margin: 0 auto 20px;
        overflow: hidden;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        color: var(--primary-color);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-name {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .profile-title {
        font-size: 16px;
        color: var(--primary-color);
        font-weight: 600;
        background: white;
        padding: 6px 20px;
        border-radius: 20px;
        display: inline-block;
    }

    .profile-body {
        padding: 30px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .info-section {
        background: #f8fafc;
        border-radius: var(--radius-sm);
        padding: 20px;
        border: 1px solid var(--border-color);
    }

    .info-section h3 {
        color: #1e293b;
        font-size: 18px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary-color);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-section h3 i {
        color: var(--primary-color);
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 15px;
    }

    .info-item i {
        width: 20px;
        color: var(--primary-color);
        margin-top: 2px;
    }

    .info-label {
        font-weight: 600;
        color: #1e293b;
        min-width: 120px;
    }

    .info-value {
        color: #64748b;
        flex: 1;
    }

    .courses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .course-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        padding: 15px;
        transition: all 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
        border-color: var(--primary-color);
    }

    .course-name {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .course-code {
        font-size: 12px;
        color: var(--primary-color);
        background: #e0f2fe;
        padding: 2px 8px;
        border-radius: 10px;
        display: inline-block;
        margin-bottom: 8px;
    }

    .course-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        font-size: 12px;
        color: #64748b;
    }

    .actions-footer {
        padding: 20px 30px;
        border-top: 1px solid var(--border-color);
        display: flex;
        gap: 10px;
        justify-content: center;
        background: #f8fafc;
    }

    .empty-courses {
        text-align: center;
        padding: 30px;
        color: #64748b;
    }

    .empty-courses i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .show-container {
            padding: 15px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .courses-grid {
            grid-template-columns: 1fr;
        }
        
        .actions-footer {
            flex-direction: column;
        }
        
        .actions-footer .btn {
            width: 100%;
        }
    }
</style>

<div class="show-container">
    <div class="page-header">
        <h1 class="page-title">تفاصيل المدرس</h1>
        <div>
            <a href="{{ route('members.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                رجوع للقائمة
            </a>
        </div>
    </div>

    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                @if($teacher->image)
                    <img src="{{ asset('images/' . $teacher->image) }}" alt="{{ $teacher->name }}" onerror="this.src='{{ asset('images/avatar.png') }}'">
                @else
                    <i class="fas fa-user-tie"></i>
                @endif
            </div>
            
            <div class="profile-name">{{ $teacher->name }}</div>
            <div class="profile-title">مدرس</div>
        </div>
        
        <div class="profile-body">
            <div class="info-grid">
                <!-- المعلومات الأساسية -->
                <div class="info-section">
                    <h3><i class="fas fa-info-circle"></i> المعلومات الأساسية</h3>
                    
                    <div class="info-item">
                        <i class="fas fa-user"></i>
                        <div class="info-label">الاسم الكامل:</div>
                        <div class="info-value">{{ $teacher->name }}</div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <div class="info-label">البريد الإلكتروني:</div>
                        <div class="info-value">{{ $teacher->email }}</div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-building"></i>
                        <div class="info-label">القسم:</div>
                        <div class="info-value">{{ $teacher->department->name ?? 'غير محدد' }}</div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-calendar-alt"></i>
                        <div class="info-label">تاريخ الانضمام:</div>
                        <div class="info-value">{{ $teacher->created_at->format('d/m/Y') }}</div>
                    </div>
                </div>
                
                <!-- المعلومات الإضافية -->
                <div class="info-section">
                    <h3><i class="fas fa-graduation-cap"></i> المعلومات الأكاديمية</h3>
                    
                    @php
                        // جلب مقررات المدرس من جدول courses
                        $courses = \App\Models\Course::where('teacher_id', $teacher->id)->get();
                        $coursesCount = $courses->count();
                    @endphp
                    
                    <div class="info-item">
                        <i class="fas fa-book"></i>
                        <div class="info-label">عدد المقررات:</div>
                        <div class="info-value">{{ $coursesCount }}</div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div class="info-label">تاريخ آخر تحديث:</div>
                        <div class="info-value">{{ $teacher->updated_at->format('d/m/Y') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- المقررات المُدرَّسة -->
            @if($coursesCount > 0)
            <div class="info-section">
                <h3><i class="fas fa-book-open"></i> المقررات المُدرَّسة ({{ $coursesCount }})</h3>
                
                <div class="courses-grid">
                    @foreach($courses as $course)
                    <div class="course-card">
                        <div class="course-name">{{ $course->course_name }}</div>
                        <div class="course-code">{{ $course->course_code }}</div>
                        
                        <div class="course-info">
                            <div>
                                <i class="fas fa-chart-line"></i>
                                الإنجاز: {{ $course->completion_ratio ?? 0 }}%
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="info-section">
                <h3><i class="fas fa-book-open"></i> المقررات المُدرَّسة</h3>
                <div class="empty-courses">
                    <i class="fas fa-book"></i>
                    <div>لا توجد مقررات حالياً</div>
                </div>
            </div>
            @endif
        </div>
        
        <div class="actions-footer">
            <a href="{{ route('members.edit', $teacher) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i>
                تعديل البيانات
            </a>
            
            <form method="POST" action="{{ route('members.destroy', $teacher) }}" style="display: inline;">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger" 
                        onclick="return confirm('هل أنت متأكد من حذف هذا المدرس؟')">
                    <i class="fas fa-trash"></i>
                    حذف المدرس
                </button>
            </form>
        </div>
    </div>
</div>
@endsection