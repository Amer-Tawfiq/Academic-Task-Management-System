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

    .reports-container {
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
        text-decoration: none;
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

    /* جدول التقارير */
    .reports-table-container {
        background: white;
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .reports-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .reports-table thead {
        background: linear-gradient(90deg, var(--primary-light), #e0f2fe);
    }

    .reports-table th {
        padding: 18px 15px;
        text-align: right;
        color: var(--dark-color);
        font-weight: 700;
        font-size: 15px;
        border-bottom: 2px solid var(--primary-color);
    }

    .reports-table td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
        transition: background 0.2s ease;
    }

    .reports-table tbody tr:hover {
        background: var(--primary-light);
    }

    .reports-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* أنواع التقارير */
    .report-type-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        display: inline-block;
        min-width: 100px;
    }

    .type-achievement {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .type-absence {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }

    .type-deprivation {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .type-results {
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        color: #0369a1;
    }

    /* حالة التقرير */
    .status-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
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

    .form-textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-family: 'Cairo', sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
        resize: vertical;
        min-height: 100px;
    }

    /* تحميل الملفات */
    .file-upload-container {
        border: 2px dashed var(--border-color);
        border-radius: var(--radius-sm);
        padding: 30px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        margin-bottom: 15px;
    }

    .file-upload-container:hover {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }

    .file-upload-container i {
        font-size: 48px;
        color: var(--gray-color);
        margin-bottom: 15px;
    }

    .file-upload-text {
        color: #64748b;
        margin-bottom: 10px;
    }

    .file-upload-hint {
        font-size: 12px;
        color: #94a3b8;
    }

    .file-preview {
        background: #f8fafc;
        padding: 15px;
        border-radius: var(--radius-sm);
        margin-top: 15px;
        display: none;
    }

    .file-preview.show {
        display: block;
    }

    .file-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .file-info i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .file-name {
        flex: 1;
        font-weight: 500;
        color: var(--dark-color);
    }

    .file-size {
        color: #64748b;
        font-size: 12px;
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

    /* زر تنزيل الملف */
    .file-download-btn {
        background: none;
        border: none;
        color: var(--primary-color);
        cursor: pointer;
        padding: 5px;
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .file-download-btn:hover {
        color: #1d4ed8;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .reports-container {
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
        
        .reports-table {
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

    /* تأثيرات إضافية */
    .report-row {
        transition: all 0.3s ease;
    }

    .report-row:hover {
        transform: translateX(-5px);
    }
</style>

<div class="reports-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <div>
            <h1 class="page-title">التقارير الأكاديمية</h1>
            <div class="page-subtitle">إدارة وتتبع التقارير الأكاديمية للمقررات والطلاب</div>
        </div>
        <button class="btn btn-success" onclick="showAddReportModal()">
            <i class="fas fa-plus"></i>
            إضافة تقرير جديد
        </button>
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
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $reports->count() }}</span>
                <span class="stat-label">إجمالي التقارير</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #065f46;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $reports->where('status', 'approved')->count() }}</span>
                <span class="stat-label">تقارير معتمدة</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $reports->where('status', 'pending')->count() }}</span>
                <span class="stat-label">بانتظار المراجعة</span>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f3e8ff, #e9d5ff); color: #7c3aed;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
                <span class="stat-number">{{ $courses->count() }}</span>
                <span class="stat-label">مقررات متاحة</span>
            </div>
        </div>
    </div>

    <!-- الفلاتر -->
    <div class="filters-container">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label class="filter-label">نوع التقرير</label>
                <select name="report_type" class="filter-select">
                    <option value="">جميع الأنواع</option>
                    <option value="achievement" {{ request('report_type') == 'achievement' ? 'selected' : '' }}>نسبة إنجاز</option>
                    <option value="absence" {{ request('report_type') == 'absence' ? 'selected' : '' }}>غياب</option>
                    <option value="deprivation" {{ request('report_type') == 'deprivation' ? 'selected' : '' }}>حرمان</option>
                    <option value="results" {{ request('report_type') == 'results' ? 'selected' : '' }}>نتائج نهائية</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">المقرر الدراسي</label>
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
                <label class="filter-label">حالة التقرير</label>
                <select name="status" class="filter-select">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>بانتظار المراجعة</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمد</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i>
                تطبيق الفلتر
            </button>
        </form>
    </div>

    <!-- جدول التقارير -->
    <div class="reports-table-container">
        @if($reports->count() > 0)
            <table class="reports-table">
                <thead>
                    <tr>
                        <th>نوع التقرير</th>
                        <th>المقرر الدراسي</th>
                        <th>الوصف</th>
                        <th>الملف</th>
                        <th>الحالة</th>
                        <th>تاريخ الإضافة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr class="report-row">
                        <td>
                            @if($report->report_type == 'achievement')
                                <span class="report-type-badge type-achievement">
                                    <i class="fas fa-chart-line"></i> نسبة إنجاز
                                </span>
                            @elseif($report->report_type == 'absence')
                                <span class="report-type-badge type-absence">
                                    <i class="fas fa-user-slash"></i> غياب
                                </span>
                            @elseif($report->report_type == 'deprivation')
                                <span class="report-type-badge type-deprivation">
                                    <i class="fas fa-times-circle"></i> حرمان
                                </span>
                            @elseif($report->report_type == 'results')
                                <span class="report-type-badge type-results">
                                    <i class="fas fa-graduation-cap"></i> نتائج نهائية
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight: 500; margin-bottom: 3px;">
                                {{ $report->course->course_name ?? 'غير محدد' }}
                            </div>
                            <div style="font-size: 12px; color: #64748b;">
                                {{ $report->course->course_code ?? '' }}
                            </div>
                        </td>
                        <td>
                            <div style="max-width: 200px;">
                                <div style="font-size: 14px; color: #334155; margin-bottom: 5px;">
                                    {{ Str::limit($report->description ?? 'لا يوجد وصف', 50) }}
                                </div>
                                <div style="font-size: 12px; color: #64748b;">
                                    <i class="fas fa-user"></i> {{ $report->creator->name ?? 'غير معروف' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($report->file_path)
                                <button class="file-download-btn" onclick="downloadFile('{{ $report->file_path }}', '{{ $report->report_type }}_{{ $report->course->course_code ?? 'report' }}')">
                                    <i class="fas fa-download"></i>
                                </button>
                            @else
                                <span style="color: #94a3b8; font-size: 12px;">لا يوجد ملف</span>
                            @endif
                        </td>
                        <td>
                            @if($report->status == 'pending')
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock"></i> بانتظار المراجعة
                                </span>
                            @elseif($report->status == 'approved')
                                <span class="status-badge status-approved">
                                    <i class="fas fa-check"></i> معتمد
                                </span>
                            @elseif($report->status == 'rejected')
                                <span class="status-badge status-rejected">
                                    <i class="fas fa-times"></i> مرفوض
                                </span>
                            @endif
                        </td>
                        <td>
                            <div style="font-size: 13px; color: #64748b;">
                                {{ $report->created_at->format('d/m/Y') }}
                            </div>
                            <div style="font-size: 12px; color: #94a3b8;">
                                {{ $report->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button class="btn btn-primary btn-sm" onclick="viewReportDetails({{ $report->id }})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                @if($report->status == 'pending')
                                <form method="POST" action="{{ route('reports.approve', $report) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{ route('reports.reject', $report) }}" style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                                
                                <form method="POST" action="{{ route('reports.destroy', $report) }}" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذا التقرير؟')">
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
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="empty-state-text">
                    لا توجد تقارير حالياً
                </div>
                <button class="btn btn-success" onclick="showAddReportModal()">
                    <i class="fas fa-plus"></i>
                    إضافة أول تقرير
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Modal لإضافة تقرير -->
<div id="addReportModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-plus-circle"></i>
                إضافة تقرير جديد
            </h3>
            <button class="modal-close" onclick="hideAddReportModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('reports.store') }}" id="addReportForm" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label class="form-label required">نوع التقرير</label>
                    <select name="report_type" class="form-input" required>
                        <option value="">اختر نوع التقرير</option>
                        <option value="achievement" {{ old('report_type') == 'achievement' ? 'selected' : '' }}>نسبة إنجاز</option>
                        <option value="absence" {{ old('report_type') == 'absence' ? 'selected' : '' }}>غياب</option>
                        <option value="deprivation" {{ old('report_type') == 'deprivation' ? 'selected' : '' }}>حرمان</option>
                        <option value="results" {{ old('report_type') == 'results' ? 'selected' : '' }}>نتائج نهائية</option>
                    </select>
                    @error('report_type')
                        <span style="color: var(--danger-color); font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label required">المقرر الدراسي</label>
                    <select name="course_id" class="form-input" required>
                        <option value="">اختر المقرر</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->course_name }} ({{ $course->course_code }})
                            </option>
                        @endforeach
                    </select>
                    @error('course_id')
                        <span style="color: var(--danger-color); font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">الوصف أو الملاحظات (اختياري)</label>
                    <textarea name="description" class="form-textarea" placeholder="أدخل وصفاً أو ملاحظات حول التقرير">{{ old('description') }}</textarea>
                    @error('description')
                        <span style="color: var(--danger-color); font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label required">رفع ملف التقرير</label>
                    <div class="file-upload-container" onclick="document.getElementById('reportFile').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <div class="file-upload-text">انقر لرفع ملف التقرير</div>
                        <div class="file-upload-hint">(PDF أو Word - الحد الأقصى 5MB)</div>
                    </div>
                    <input type="file" id="reportFile" name="file" accept=".pdf,.doc,.docx" style="display: none;" onchange="previewFile(this)">
                    <div class="file-preview" id="filePreview">
                        <div class="file-info">
                            <i class="fas fa-file"></i>
                            <div class="file-name" id="fileName"></div>
                            <div class="file-size" id="fileSize"></div>
                        </div>
                    </div>
                    @error('file')
                        <span style="color: var(--danger-color); font-size: 12px; margin-top: 5px;">{{ $message }}</span>
                    @enderror
                </div>
                
                <input type="hidden" name="created_by" value="{{ Auth::id() }}">
                <input type="hidden" name="status" value="pending">
            </form>
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-cancel" onclick="hideAddReportModal()">
                <i class="fas fa-times"></i>
                إلغاء
            </button>
            <button type="submit" form="addReportForm" class="btn btn-success">
                <i class="fas fa-paper-plane"></i>
                إرسال التقرير
            </button>
        </div>
    </div>
</div>

<!-- Modal لعرض تفاصيل التقرير -->
<div id="reportDetailsModal" class="modal-overlay">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-file-alt"></i>
                تفاصيل التقرير
            </h3>
            <button class="modal-close" onclick="hideReportDetailsModal()">&times;</button>
        </div>
        <div class="modal-body" id="reportDetailsContent">
            <!-- سيتم تعبئة المحتوى عبر JavaScript -->
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-cancel" onclick="hideReportDetailsModal()">
                <i class="fas fa-times"></i>
                إغلاق
            </button>
        </div>
    </div>
</div>

<script>
    // إدارة الـ Modals
    function showAddReportModal() {
        const modal = document.getElementById('addReportModal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function hideAddReportModal() {
        const modal = document.getElementById('addReportModal');
        modal.style.display = 'none';
        document.getElementById('addReportForm').reset();
        document.getElementById('filePreview').classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function viewReportDetails(reportId) {
        // عرض مؤشر التحميل
        document.getElementById('reportDetailsContent').innerHTML = `
            <div style="text-align: center; padding: 40px;">
                <i class="fas fa-spinner fa-spin fa-2x" style="color: var(--primary-color);"></i>
                <div style="margin-top: 15px; color: #64748b;">جاري تحميل تفاصيل التقرير...</div>
            </div>
        `;
        
        const modal = document.getElementById('reportDetailsModal');
        modal.style.display = 'block';
        
        // جلب بيانات التقرير
        fetch(`/reports/${reportId}/details`)
            .then(response => response.json())
            .then(data => {
                let html = `
                    <div style="margin-bottom: 30px;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                            <div style="font-size: 14px; color: #64748b;">رقم التقرير:</div>
                            <div style="font-weight: 600; color: var(--dark-color);">#${reportId}</div>
                        </div>
                        
                        <div style="background: #f8fafc; padding: 20px; border-radius: var(--radius-sm); margin-bottom: 20px;">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                                <div>
                                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">نوع التقرير</div>
                                    <div style="font-weight: 600;">
                `;
                
                if (data.report_type == 'achievement') {
                    html += `<span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 13px;">نسبة إنجاز</span>`;
                } else if (data.report_type == 'absence') {
                    html += `<span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 13px;">غياب</span>`;
                } else if (data.report_type == 'deprivation') {
                    html += `<span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 13px;">حرمان</span>`;
                } else if (data.report_type == 'results') {
                    html += `<span style="background: #e0f2fe; color: #0369a1; padding: 4px 12px; border-radius: 20px; font-size: 13px;">نتائج نهائية</span>`;
                }
                
                html += `
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">حالة التقرير</div>
                                    <div style="font-weight: 600;">
                `;
                
                if (data.status == 'pending') {
                    html += `<span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 20px; font-size: 13px;">بانتظار المراجعة</span>`;
                } else if (data.status == 'approved') {
                    html += `<span style="background: #d1fae5; color: #065f46; padding: 4px 12px; border-radius: 20px; font-size: 13px;">معتمد</span>`;
                } else if (data.status == 'rejected') {
                    html += `<span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 13px;">مرفوض</span>`;
                }
                
                html += `
                                    </div>
                                </div>
                                
                                <div>
                                    <div style="font-size: 12px; color: #64748b; margin-bottom: 5px;">تاريخ الإضافة</div>
                                    <div style="font-weight: 600;">${data.created_at}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <div style="font-size: 14px; color: #64748b; margin-bottom: 10px;">المقرر الدراسي</div>
                            <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 15px;">
                                <div style="font-weight: 600; color: var(--dark-color); margin-bottom: 5px;">${data.course_name}</div>
                                <div style="color: #64748b; font-size: 13px;">${data.course_code}</div>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 20px;">
                            <div style="font-size: 14px; color: #64748b; margin-bottom: 10px;">المنشئ</div>
                            <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 15px;">
                                <div style="font-weight: 600; color: var(--dark-color); margin-bottom: 5px;">${data.creator_name}</div>
                                <div style="color: #64748b; font-size: 13px;">${data.creator_email}</div>
                            </div>
                        </div>
                `;
                
                if (data.description) {
                    html += `
                        <div style="margin-bottom: 20px;">
                            <div style="font-size: 14px; color: #64748b; margin-bottom: 10px;">الوصف والملاحظات</div>
                            <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 15px;">
                                <div style="color: var(--dark-color); line-height: 1.6;">${data.description}</div>
                            </div>
                        </div>
                    `;
                }
                
                if (data.file_path) {
                    html += `
                        <div style="margin-bottom: 20px;">
                            <div style="font-size: 14px; color: #64748b; margin-bottom: 10px;">ملف التقرير</div>
                            <div style="background: white; border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <i class="fas fa-file" style="font-size: 24px; color: var(--primary-color);"></i>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; color: var(--dark-color); margin-bottom: 5px;">ملف التقرير</div>
                                        <div style="color: #64748b; font-size: 13px;">${data.file_name || 'report.pdf'}</div>
                                    </div>
                                    <button class="btn btn-primary btn-sm" onclick="downloadFile('${data.file_path}', '${data.file_name || 'report'}')">
                                        <i class="fas fa-download"></i> تنزيل
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                document.getElementById('reportDetailsContent').innerHTML = html;
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                document.getElementById('reportDetailsContent').innerHTML = `
                    <div style="text-align: center; padding: 40px; color: var(--danger-color);">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                        <div style="margin-top: 15px;">حدث خطأ في تحميل تفاصيل التقرير</div>
                    </div>
                `;
            });
    }

    function hideReportDetailsModal() {
        const modal = document.getElementById('reportDetailsModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // معاينة الملف
    function previewFile(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const preview = document.getElementById('filePreview');
            const fileName = document.getElementById('fileName');
            const fileSize = document.getElementById('fileSize');
            
            // التحقق من حجم الملف (5MB كحد أقصى)
            const maxSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxSize) {
                alert('حجم الملف يجب أن يكون أقل من 5MB');
                input.value = '';
                preview.classList.remove('show');
                return;
            }
            
            // التحقق من نوع الملف
            const allowedTypes = ['.pdf', '.doc', '.docx'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (!allowedTypes.includes(fileExtension)) {
                alert('الرجاء رفع ملف PDF أو Word فقط');
                input.value = '';
                preview.classList.remove('show');
                return;
            }
            
            // عرض معلومات الملف
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            preview.classList.add('show');
        }
    }

    // تنسيق حجم الملف
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // تنزيل الملف
    function downloadFile(filePath, fileName) {
        const link = document.createElement('a');
        link.href = filePath;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // إغلاق النوافذ بالضغط خارجها
    window.onclick = function(event) {
        if (event.target.classList.contains('modal-overlay')) {
            hideAddReportModal();
            hideReportDetailsModal();
        }
    }

    // إغلاق النوافذ بالضغط على ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            hideAddReportModal();
            hideReportDetailsModal();
        }
    });

    // التأكد من إغلاق الـ Modal عند إرسال النموذج
    document.getElementById('addReportForm')?.addEventListener('submit', function(e) {
        setTimeout(() => {
            hideAddReportModal();
        }, 100);
    });
</script>
@endsection