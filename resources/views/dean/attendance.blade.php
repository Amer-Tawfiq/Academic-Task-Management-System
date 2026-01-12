@extends('layouts.dean')

@section('content')
<div class="attendance-container">
    <div class="page-header">
        <h1>
            <i class="fas fa-user-check"></i>
            متابعة الحضور والغياب
        </h1>
        <p>نظرة عامة على حضور الطلاب في جميع الأقسام</p>
    </div>

    <!-- بطاقات إحصائية -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon blue"><i class="fas fa-users"></i></div>
            <div class="details">
                <h3>95%</h3>
                <span>نسبة الحضور اليوم</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon green"><i class="fas fa-check-circle"></i></div>
            <div class="details">
                <h3>1,250</h3>
                <span>حاضر اليوم</span>
            </div>
        </div>
        <div class="stat-card red">
            <div class="icon red"><i class="fas fa-times-circle"></i></div>
            <div class="details">
                <h3>45</h3>
                <span>غائب اليوم</span>
            </div>
        </div>
    </div>

    <!-- الفلترة -->
    <div class="filters-card">
        <div class="card-header">
            <h3><i class="fas fa-filter"></i> تصفية السجلات</h3>
        </div>
        <form class="filters-form">
            <div class="form-row">
                <div class="form-group">
                    <label>القسم</label>
                    <select name="department_id" class="form-control" id="departmentSelect">
                        <option value="">جميع الأقسام</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>المقرر</label>
                    <select name="course_id" class="form-control">
                        <option value="">جميع المقررات</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" data-dept="{{ $course->department_id }}">
                                {{ $course->course_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label>التاريخ</label>
                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group align-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> عرض السجلات
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- جدول الحضور -->
    <div class="attendance-table-card">
        <div class="empty-state-placeholder">
            <i class="fas fa-chart-bar"></i>
            <h3>اختر معايير العرض</h3>
            <p>قم باختيار القسم والمقرر لعرض إحصائيات الحضور التفصيلية</p>
        </div>
    </div>
</div>

<style>
    .attendance-container {
        padding: 20px;
        font-family: 'Cairo', sans-serif;
    }

    .page-header {
        margin-bottom: 30px;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 20px;
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

    .stat-card .icon.blue { background: #e0f2fe; color: #0284c7; }
    .stat-card .icon.green { background: #dcfce7; color: #16a34a; }
    .stat-card .icon.red { background: #fee2e2; color: #dc2626; }

    .stat-card .details h3 { margin: 0; font-size: 24px; color: #0f172a; }
    .stat-card .details span { color: #64748b; font-size: 14px; }

    /* Filters */
    .filters-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        border: 1px solid #e2e8f0;
    }

    .card-header {
        padding: 15px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-header h3 { margin: 0; font-size: 16px; color: #475569; }

    .filters-form { padding: 20px; }

    .form-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #64748b;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-family: inherit;
    }

    .form-group.align-end {
        display: flex;
        align-items: flex-end;
        flex: 0 0 auto;
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: background 0.2s;
    }

    .btn-primary:hover { background: #2563eb; }

    /* Empty state */
    .attendance-table-card {
        background: white;
        border-radius: 12px;
        min-height: 300px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
    }

    .empty-state-placeholder {
        text-align: center;
        color: #94a3b8;
    }

    .empty-state-placeholder i { font-size: 48px; margin-bottom: 15px; opacity: 0.5; }
    .empty-state-placeholder h3 { color: #475569; margin-bottom: 5px; }
</style>

<script>
    document.getElementById('departmentSelect').addEventListener('change', function() {
        const deptId = this.value;
        const courseSelect = document.querySelector('select[name="course_id"]');
        const options = courseSelect.options;
        
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const optionDept = option.getAttribute('data-dept');
            
            if (option.value === "") {
                continue; // Skip "All Courses" option
            }
            
            if (deptId === "" || optionDept === deptId) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        }
        
        // Reset selection if hidden
        if (courseSelect.selectedOptions[0].style.display === "none") {
            courseSelect.value = "";
        }
    });
</script>
@endsection
