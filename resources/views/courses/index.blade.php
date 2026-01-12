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

    .courses-container {
        padding: 25px;
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
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 180px;
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

    .btn-info {
        background: linear-gradient(135deg, var(--info-color), #0284c7);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, var(--danger-color), #dc2626);
        color: white;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 13px;
    }

    .btn-group {
        display: flex;
        gap: 8px;
    }

    .courses-table-container {
        background: white;
        border-radius: var(--radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .courses-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .courses-table thead {
        background: linear-gradient(90deg, var(--primary-light), #e0f2fe);
    }

    .courses-table th {
        padding: 18px 15px;
        text-align: right;
        color: var(--dark-color);
        font-weight: 700;
        font-size: 15px;
        border-bottom: 2px solid var(--primary-color);
    }

    .courses-table td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
        transition: background 0.2s ease;
    }

    .courses-table tbody tr:hover {
        background: var(--primary-light);
    }

    .courses-table tbody tr:last-child td {
        border-bottom: none;
    }

    .progress-container {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
    }

    .progress-bar {
        flex: 1;
        height: 10px;
        background: #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .progress-text {
        min-width: 40px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
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
    }

    /* Modal Styles - IMPROVED */
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
        max-width: 500px;
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
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        flex-shrink: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .courses-container {
            padding: 15px;
        }
        
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .courses-table {
            display: block;
            overflow-x: auto;
        }
        
        .modal-content {
            width: 95%;
            margin: 10px auto;
            padding: 20px;
            max-height: calc(100vh - 20px);
        }
    }

    /* Course Status Colors */
    .status-high { background: linear-gradient(90deg, #10b981, #34d399); }
    .status-medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
    .status-low { background: linear-gradient(90deg, #ef4444, #f87171); }

    /* تحسين عناصر النموذج */
    .form-range-container {
        padding: 10px 0;
    }

    .form-range-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .range-percent {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 16px;
    }

    input[type="range"] {
        width: 100%;
        height: 8px;
        -webkit-appearance: none;
        background: #e2e8f0;
        border-radius: 10px;
        outline: none;
    }

    input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 24px;
        height: 24px;
        background: var(--primary-color);
        border-radius: 50%;
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: all 0.2s ease;
    }

    input[type="range"]::-webkit-slider-thumb:hover {
        transform: scale(1.1);
    }

    /* زر الإلغاء المعدل */
    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }
</style>

<div class="courses-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1 class="page-title">إدارة المقررات</h1>
        <button class="btn btn-success" onclick="showAddModal()">
            <i class="fas fa-plus"></i>
            إضافة مقرر جديد
        </button>
    </div>

    <!-- الفلاتر -->
    <div class="filters-container">
        <form method="GET" class="filter-form">
            <div class="filter-group">
                <label class="filter-label">القسم</label>
                <select name="department_id" class="filter-select">
                    <option value="">جميع الأقسام</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-label">نسبة الإنجاز</label>
                <select name="completion_filter" class="filter-select">
                    <option value="">الكل</option>
                    <option value="low" {{ request('completion_filter') == 'low' ? 'selected' : '' }}>منخفضة (أقل من 30%)</option>
                    <option value="medium" {{ request('completion_filter') == 'medium' ? 'selected' : '' }}>متوسطة (30% - 70%)</option>
                    <option value="high" {{ request('completion_filter') == 'high' ? 'selected' : '' }}>عالية (أكثر من 70%)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i>
                تطبيق الفلتر
            </button>
        </form>
    </div>

    <!-- جدول المقررات -->
    <div class="courses-table-container">
        @if($courses->count() > 0)
            <table class="courses-table">
                <thead>
                    <tr>
                        <th>اسم المقرر</th>
                        <th>كود المقرر</th>
                        <th>القسم</th>
                        <th>المدرس</th>
                        <th>نسبة الإنجاز</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #1e293b; margin-bottom: 5px;">
                                {{ $course->course_name }}
                            </div>
                            <div style="font-size: 13px; color: #64748b;">
                                {{ $course->description ?? 'لا يوجد وصف' }}
                            </div>
                        </td>
                        <td>
                            <span style="background: #e0f2fe; color: #0369a1; padding: 4px 10px; 
                                  border-radius: 20px; font-weight: 600; font-size: 13px;">
                                {{ $course->course_code }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight: 500; margin-bottom: 3px;">{{ $course->department->name ?? 'غير محدد' }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $course->department->code ?? '' }}</div>
                        </td>
                        <td>
                            <div style="font-weight: 500; margin-bottom: 3px;">{{ $course->teacher->name ?? 'غير محدد' }}</div>
                            <div style="font-size: 12px; color: #64748b;">
                                {{ $course->teacher->role->first()->role_name ?? 'دكتور' }}
                            </div>
                        </td>
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar">
                                    <div class="progress-fill 
                                        {{ $course->completion_ratio < 30 ? 'status-low' : 
                                          ($course->completion_ratio < 70 ? 'status-medium' : 'status-high') }}"
                                        style="width: {{ $course->completion_ratio }}%">
                                    </div>
                                </div>
                                <span class="progress-text">
                                    {{ $course->completion_ratio }}%
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-info btn-sm" onclick="showEditModal({{ json_encode($course) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذا المقرر؟')">
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
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="empty-state-text">
                    لا توجد مقررات لعرضها
                </div>
                <button class="btn btn-success" style="margin-top: 20px;" onclick="showAddModal()">
                    <i class="fas fa-plus"></i>
                    إضافة أول مقرر
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Modal للإضافة -->
<div id="addModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">إضافة مقرر جديد</h3>
            <button class="modal-close" onclick="hideAddModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('courses.store') }}" id="addForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">اسم المقرر</label>
                    <input type="text" name="course_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">كود المقرر</label>
                    <input type="text" name="course_code" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">القسم</label>
                    <select name="department_id" class="form-input" required>
                        <option value="">اختر القسم</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">المدرس</label>
                    <select name="teacher_id" class="form-input" required>
                        <option value="">اختر المدرس</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->name }} 
                                ({{ $teacher->role->first()->role_name ?? 'دكتور' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">وصف المقرر (اختياري)</label>
                    <textarea name="description" class="form-input" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <div class="form-range-label">
                        <label class="form-label">نسبة الإنجاز</label>
                        <span id="addPercent" class="range-percent">0%</span>
                    </div>
                    <div class="form-range-container">
                        <input type="range" name="completion_ratio" min="0" max="100" value="0" 
                               oninput="document.getElementById('addPercent').textContent = this.value + '%'">
                    </div>
                </div>
            </form>
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-cancel" onclick="hideAddModal()">
                إلغاء
            </button>
            <button type="submit" form="addForm" class="btn btn-success">
                <i class="fas fa-save"></i>
                حفظ المقرر
            </button>
        </div>
    </div>
</div>

<!-- Modal للتعديل -->
<div id="editModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">تعديل المقرر</h3>
            <button class="modal-close" onclick="hideEditModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label">اسم المقرر</label>
                    <input type="text" name="course_name" id="edit_course_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">كود المقرر</label>
                    <input type="text" name="course_code" id="edit_course_code" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">القسم</label>
                    <select name="department_id" id="edit_department_id" class="form-input" required>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">المدرس</label>
                    <select name="teacher_id" id="edit_teacher_id" class="form-input" required>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->name }} 
                                ({{ $teacher->role->first()->role_name ?? 'دكتور' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">وصف المقرر (اختياري)</label>
                    <textarea name="description" id="edit_description" class="form-input" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <div class="form-range-label">
                        <label class="form-label">نسبة الإنجاز</label>
                        <span id="editPercent" class="range-percent">0%</span>
                    </div>
                    <div class="form-range-container">
                        <input type="range" name="completion_ratio" id="edit_completion_ratio" min="0" max="100" 
                               oninput="document.getElementById('editPercent').textContent = this.value + '%'">
                    </div>
                </div>
            </form>
        </div>
        <div class="form-actions">
            <button type="button" class="btn btn-cancel" onclick="hideEditModal()">
                إلغاء
            </button>
            <button type="submit" form="editForm" class="btn btn-info">
                <i class="fas fa-sync-alt"></i>
                تحديث البيانات
            </button>
        </div>
    </div>
</div>

<script>
    const body = document.body;

    function toggleModal(id, show = true){
    const modal = document.getElementById(id);
    modal.style.display = show ? 'block' : 'none';
    body.style.overflow = show ? 'hidden' : 'auto';
    }

    function showAddModal(){ toggleModal('addModal',true); }
    function hideAddModal(){ document.getElementById('addForm').reset(); toggleModal('addModal',false); }

    function showEditModal(course){
    toggleModal('editModal',true);
    Object.keys(course).forEach(k=>{
    const el = document.getElementById('edit_'+k);
    if(el) el.value = course[k];
    });
    document.getElementById('editPercent').textContent = course.completion_ratio+'%';
    document.getElementById('editForm').action = '/courses/'+course.id;
    }

    function hideEditModal(){ toggleModal('editModal',false); }

    window.onclick = e=>{
    if(e.target.classList.contains('modal-overlay')){
    hideAddModal(); hideEditModal();
    }
    }

    document.addEventListener('keydown',e=>{
    if(e.key==='Escape'){ hideAddModal(); hideEditModal(); }
    });

    document.querySelectorAll('input[type="range"]').forEach(r=>{
    r.oninput=()=>document.getElementById(
    r.id==='edit_completion_ratio'?'editPercent':'addPercent'
    ).textContent=r.value+'%';
    });
</script>

@endsection