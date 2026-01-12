@extends('layouts.dean')

@section('content')
<div class="courses-container">
    <div class="page-header">
        <h1>
            <i class="fas fa-graduation-cap"></i>
            المقررات الدراسية
        </h1>
        <p>إدارة واستعراض الدورات التدريبية والمقررات ({{ $courses->count() }})</p>
    </div>

    <!-- بطاقات إحصائية سريعة -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="icon"><i class="fas fa-book"></i></div>
            <div class="details">
                <h3>{{ $courses->count() }}</h3>
                <span>إجمالي المقررات</span>
            </div>
        </div>
        <div class="stat-card green">
            <div class="icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="details">
                <h3>{{ $courses->unique('teacher_id')->count() }}</h3>
                <span>المعلمون</span>
            </div>
        </div>
        <div class="stat-card purple">
            <div class="icon"><i class="fas fa-users"></i></div>
            <div class="details">
                <h3>{{ $courses->sum('students_count') }}</h3>
                <span>إجمالي الطلاب</span>
            </div>
        </div>
    </div>

    <!-- جدول المقررات -->
    <div class="table-container">
        <table class="courses-table">
            <thead>
                <tr>
                    <th>الكود</th>
                    <th>اسم المقرر</th>
                    <th>المدرس</th>
                    <th>القسم</th>
                    <th>الطلاب</th>
                    <th>نسبة الإنجاز</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td><span class="badge-code">{{ $course->course_code }}</span></td>
                    <td>
                        <div class="course-name">{{ $course->course_name }}</div>
                    </td>
                    <td>
                        @if($course->teacher)
                            <div class="teacher-info">
                                <div class="teacher-avatar">
                                    {{ substr($course->teacher->name, 0, 1) }}
                                </div>
                                <span>{{ $course->teacher->name }}</span>
                            </div>
                        @else
                            <span class="text-muted">غير محدد</span>
                        @endif
                    </td>
                    <td>{{ $course->department->name ?? 'غير محدد' }}</td>
                    <td>
                        <span class="badge-students">
                            <i class="fas fa-user-graduate"></i> {{ $course->students_count }}
                        </span>
                    </td>
                    <td>
                        <div class="progress-wrapper">
                            <div class="progress-bar" style="width: {{ $course->completion_ratio ?? 0 }}%"></div>
                        </div>
                        <span class="progress-text">{{ $course->completion_ratio ?? 0 }}%</span>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="#" class="btn-icon view" title="عرض"><i class="fas fa-eye"></i></a>
                            <a href="#" class="btn-icon edit" title="تعديل"><i class="fas fa-edit"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="fas fa-folder-open"></i>
                        <p>لا توجد مقررات دراسية حالياً</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .courses-container {
        padding: 20px;
        font-family: 'Cairo', sans-serif;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-header h1 {
        font-size: 24px;
        color: #1e293b;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .page-header h1 i { color: #3b82f6; }
    .page-header p { color: #64748b; margin: 0; }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid #e2e8f0;
    }

    .stat-card .icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-card.blue .icon { background: #e0f2fe; color: #0284c7; }
    .stat-card.green .icon { background: #dcfce7; color: #16a34a; }
    .stat-card.purple .icon { background: #f3e8ff; color: #9333ea; }

    .stat-card .details h3 { margin: 0; font-size: 24px; color: #0f172a; }
    .stat-card .details span { color: #64748b; font-size: 14px; }

    /* Table */
    .table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .courses-table {
        width: 100%;
        border-collapse: collapse;
    }

    .courses-table th {
        background: #f8fafc;
        padding: 15px;
        text-align: right;
        font-weight: 600;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
    }

    .courses-table td {
        padding: 15px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    .courses-table tr:hover { background: #f8fafc; }

    .badge-code {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 8px;
        border-radius: 6px;
        font-family: monospace;
        font-weight: 600;
    }

    .course-name { font-weight: 600; color: #0f172a; }

    .teacher-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .teacher-avatar {
        width: 30px;
        height: 30px;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    .badge-students {
        background: #f0fdf4;
        color: #166534;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
    }

    .progress-wrapper {
        background: #e2e8f0;
        height: 6px;
        border-radius: 3px;
        width: 100px;
        display: inline-block;
        margin-left: 10px;
    }

    .progress-bar {
        background: #3b82f6;
        height: 100%;
        border-radius: 3px;
    }

    .progress-text { font-size: 12px; color: #64748b; }

    .actions { display: flex; gap: 5px; }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-icon.view { background: #e0f2fe; color: #0284c7; }
    .btn-icon.view:hover { background: #0284c7; color: white; }

    .btn-icon.edit { background: #fef9c3; color: #ca8a04; }
    .btn-icon.edit:hover { background: #ca8a04; color: white; }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: #94a3b8;
    }

    .empty-state i { font-size: 40px; margin-bottom: 10px; opacity: 0.5; }
</style>
@endsection
