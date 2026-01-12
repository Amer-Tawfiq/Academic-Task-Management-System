@extends('layouts.teacher')

@section('styles')
<style>
    .attendance-container {
        padding: 20px;
    }
    
    .attendance-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 20px;
    }
    
    .attendance-header h1 {
        color: #1e293b;
        font-size: 28px;
        margin-bottom: 10px;
    }
    
    /* الفلاتر */
    .attendance-filters {
        background: white;
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .filter-group {
        margin-bottom: 0;
    }
    
    .filter-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #475569;
        font-size: 15px;
    }
    
    .filter-select {
        width: 100%;
        padding: 10px 15px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 15px;
        font-family: 'Cairo', sans-serif;
        transition: all 0.3s ease;
        background: white;
    }
    
    .filter-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    
    /* جدول الحضور */
    .attendance-table-container {
        background: white;
        border-radius: 12px;
        overflow: auto;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        position: relative;
    }
    
    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    
    .attendance-table th {
        background: #3b82f6;
        padding: 16px 12px;
        text-align: center;
        font-weight: 600;
        color: white;
        border: 1px solid #2563eb;
        font-size: 15px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    
    .attendance-table th.student-col {
        background: #1e40af;
        position: sticky;
        left: 0;
        z-index: 20;
        min-width: 150px;
    }
    
    .attendance-table td {
        padding: 12px;
        border: 1px solid #e2e8f0;
        text-align: center;
        vertical-align: middle;
    }
    
    .attendance-table td.student-col {
        background: #f8fafc;
        position: sticky;
        left: 0;
        z-index: 5;
        font-weight: 500;
        color: #1e293b;
        min-width: 150px;
    }
    
    /* خلايا الحضور */
    .attendance-cell {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        padding: 8px 4px;
        border-radius: 6px;
        min-height: 60px;
    }
    
    .attendance-cell:hover {
        background: #f1f5f9;
        transform: scale(1.05);
    }
    
    .attendance-cell.present {
        background: #d1fae5;
        color: #065f46;
        border: 2px solid #34d399;
    }
    
    .attendance-cell.absent {
        background: #fee2e2;
        color: #991b1b;
        border: 2px solid #f87171;
    }
    
    .attendance-cell.empty {
        background: #f8fafc;
        color: #64748b;
        border: 1px dashed #cbd5e1;
    }
    
    .status-icon {
        font-size: 18px;
        margin-bottom: 4px;
    }
    
    .status-text {
        font-size: 13px;
        font-weight: 600;
    }
    
    /* أيام الأسبوع */
    .day-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }
    
    .day-name {
        font-weight: 600;
        font-size: 14px;
    }
    
    .day-number {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 12px;
    }
    
    /* أزرار الإجراءات */
    .actions-section {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
    
    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }
    
    .btn-secondary:hover {
        background: #e2e8f0;
    }
    
    /* إحصائيات */
    .attendance-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .stat-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .stat-label {
        color: #64748b;
        font-size: 14px;
    }
    
    /* إذا لم توجد بيانات */
    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
    }
    
    .no-data-icon {
        font-size: 60px;
        color: #cbd5e1;
        margin-bottom: 20px;
    }
    
    /* تحميل */
    .loading {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        z-index: 100;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
    
    .loading-spinner {
        width: 50px;
        height: 50px;
        border: 4px solid #e2e8f0;
        border-top: 4px solid #3b82f6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* التجاوب */
    @media (max-width: 768px) {
        .attendance-table-container {
            font-size: 13px;
        }
        
        .attendance-table th,
        .attendance-table td {
            padding: 8px 6px;
        }
        
        .attendance-cell {
            min-height: 50px;
            padding: 6px 2px;
        }
        
        .status-icon {
            font-size: 16px;
        }
        
        .status-text {
            font-size: 11px;
        }
        
        .actions-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 576px) {
        .attendance-filters {
            grid-template-columns: 1fr;
        }
        
        .attendance-stats {
            grid-template-columns: 1fr;
        }
        
        .attendance-header h1 {
            font-size: 24px;
        }
    }
</style>
@endsection

@section('content')
<div class="attendance-container">
    <div class="attendance-header">
        <h1><i class="fas fa-user-check"></i> سجل الحضور والغياب</h1>
        <p>تسجيل حضور وغياب الطلاب للمقررات الدراسية</p>
    </div>
    
    <!-- فلترة المقرر والأسبوع -->
    <div class="attendance-filters">
        <div class="filter-group">
            <label class="filter-label">
                <i class="fas fa-book"></i> اختر المقرر:
            </label>
            <select id="courseSelect" class="filter-select" onchange="filterAttendance()">
                <option value="">اختر المقرر</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ $selectedCourseId == $course->id ? 'selected' : '' }}>
                        {{ $course->course_code }} - {{ $course->course_name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="filter-group">
            <label class="filter-label">
                <i class="fas fa-calendar-week"></i> اختر الأسبوع:
            </label>
            <select id="weekSelect" class="filter-select" onchange="filterAttendance()">
                @for($week = 1; $week <= 16; $week++)
                    <option value="{{ $week }}" {{ $selectedWeek == $week ? 'selected' : '' }}>
                        الأسبوع {{ $week }}
                        @if(in_array($week, $availableWeeks ?? []))
                            ✓
                        @endif
                    </option>
                @endfor
            </select>
        </div>
    </div>
    
    <!-- إحصائيات -->
    @if($selectedCourseId && $students->count() > 0)
        <div class="attendance-stats">
            <div class="stat-card">
                <div class="stat-number">{{ $students->count() }}</div>
                <div class="stat-label">عدد الطلاب</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ count($days) }}</div>
                <div class="stat-label">أيام الدراسة</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $selectedWeek }}</div>
                <div class="stat-label">الأسبوع الدراسي</div>
            </div>
        </div>
    @endif
    
    <!-- جدول الحضور -->
    <div class="attendance-table-container">
        @if($selectedCourseId && $students->count() > 0)
            <div class="loading" id="loadingIndicator">
                <div class="loading-spinner"></div>
            </div>
            
            <table class="attendance-table" id="attendanceTable">
                <thead>
                    <tr>
                        <th class="student-col">اسم الطالب</th>
                        @foreach($days as $dayNum => $dayName)
                            <th>
                                <div class="day-header">
                                    <span class="day-name">{{ $dayName }}</span>
                                    <span class="day-number">{{ $dayNum }}</span>
                                </div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td class="student-col">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #3b82f6; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                        {{ mb_substr($student->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $student->name }}</div>
                                        <small style="color: #64748b; font-size: 12px;">{{ $student->student_id ?? 'لا يوجد' }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            @foreach($days as $dayNum => $dayName)
                                @php
                                    $recordKey = $student->id . '_' . $dayNum;
                                    $status = $attendanceRecords[$recordKey]->status ?? null;
                                    $statusClass = $status ? ($status == 'present' ? 'present' : 'absent') : 'empty';
                                    $statusText = $status ? ($status == 'present' ? 'حاضر' : 'غائب') : 'غير مسجل';
                                    $statusIcon = $status ? ($status == 'present' ? 'fas fa-check' : 'fas fa-times') : 'fas fa-minus';
                                @endphp
                                
                                <td>
                                    <div class="attendance-cell {{ $statusClass }}"
                                         onclick="toggleAttendance(this, {{ $student->id }}, {{ $dayNum }})"
                                         data-status="{{ $status }}"
                                         data-student="{{ $student->id }}"
                                         data-day="{{ $dayNum }}">
                                        <i class="status-icon {{ $statusIcon }}"></i>
                                        <span class="status-text">{{ $statusText }}</span>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                <div class="no-data-icon">
                    <i class="fas fa-users-slash"></i>
                </div>
                <h3>لا توجد بيانات للعرض</h3>
                <p>@if(!$selectedCourseId) الرجاء اختيار مقرر أولاً @else لا يوجد طلاب مسجلين في هذا المقرر @endif</p>
            </div>
        @endif
    </div>
    
    <!-- أزرار الإجراءات -->
    @if($selectedCourseId && $students->count() > 0)
        <div class="actions-section">
            <div>
                <button class="btn btn-secondary" onclick="markAllPresent()">
                    <i class="fas fa-check-circle"></i> تسجيل الكل حاضر
                </button>
                <button class="btn btn-secondary" onclick="markAllAbsent()">
                    <i class="fas fa-times-circle"></i> تسجيل الكل غائب
                </button>
            </div>
            <button class="btn btn-primary" onclick="saveAttendance()">
                <i class="fas fa-save"></i> حفظ التعديلات
            </button>
        </div>
    @endif
</div>

<script>
    // بيانات الجلسة
    let attendanceData = {};
    let currentCourseId = {{ $selectedCourseId ?? 'null' }};
    let currentWeek = {{ $selectedWeek ?? 1 }};
    
    // فلترة البيانات
    function filterAttendance() {
        const courseId = document.getElementById('courseSelect').value;
        const week = document.getElementById('weekSelect').value;
        
        if (courseId) {
            window.location.href = `/teacher/attendance?course_id=${courseId}&week=${week}`;
        }
    }
    
    // تبديل حالة الحضور
    function toggleAttendance(cell, studentId, day) {
        const currentStatus = cell.getAttribute('data-status');
        let newStatus;
        
        if (!currentStatus || currentStatus === 'absent') {
            newStatus = 'present';
        } else {
            newStatus = 'absent';
        }
        
        // تحديث الواجهة
        updateCellAppearance(cell, newStatus);
        
        // تحديث البيانات
        if (!attendanceData[studentId]) {
            attendanceData[studentId] = {};
        }
        attendanceData[studentId][day] = newStatus;
        
        // إظهار التغيير
        showUnsavedChanges();
    }
    
    // تحديث مظهر الخلية
    function updateCellAppearance(cell, status) {
        const icon = cell.querySelector('.status-icon');
        const text = cell.querySelector('.status-text');
        
        cell.setAttribute('data-status', status);
        cell.className = 'attendance-cell ' + (status === 'present' ? 'present' : 'absent');
        
        if (status === 'present') {
            icon.className = 'status-icon fas fa-check';
            text.textContent = 'حاضر';
        } else {
            icon.className = 'status-icon fas fa-times';
            text.textContent = 'غائب';
        }
    }
    
    // تسجيل الكل حاضر
    function markAllPresent() {
        const cells = document.querySelectorAll('.attendance-cell');
        cells.forEach(cell => {
            const studentId = cell.getAttribute('data-student');
            const day = cell.getAttribute('data-day');
            
            if (studentId && day) {
                updateCellAppearance(cell, 'present');
                
                if (!attendanceData[studentId]) {
                    attendanceData[studentId] = {};
                }
                attendanceData[studentId][day] = 'present';
            }
        });
        
        showUnsavedChanges();
    }
    
    // تسجيل الكل غائب
    function markAllAbsent() {
        const cells = document.querySelectorAll('.attendance-cell');
        cells.forEach(cell => {
            const studentId = cell.getAttribute('data-student');
            const day = cell.getAttribute('data-day');
            
            if (studentId && day) {
                updateCellAppearance(cell, 'absent');
                
                if (!attendanceData[studentId]) {
                    attendanceData[studentId] = {};
                }
                attendanceData[studentId][day] = 'absent';
            }
        });
        
        showUnsavedChanges();
    }
    
    // إظهار أن هناك تغييرات غير محفوظة
    function showUnsavedChanges() {
        const saveBtn = document.querySelector('.btn-primary');
        if (saveBtn) {
            saveBtn.innerHTML = '<i class="fas fa-exclamation-circle"></i> هناك تغييرات غير محفوظة';
            saveBtn.style.background = '#f59e0b';
        }
    }
    
    // حفظ الحضور
    async function saveAttendance() {
        if (!currentCourseId) {
            alert('الرجاء اختيار مقرر أولاً');
            return;
        }
        
        if (Object.keys(attendanceData).length === 0) {
            alert('لم تقم بأي تغييرات');
            return;
        }
        
        // إظهار مؤشر التحميل
        const loadingIndicator = document.getElementById('loadingIndicator');
        if (loadingIndicator) loadingIndicator.style.display = 'flex';
        
        try {
            const response = await fetch('/teacher/attendance/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    course_id: currentCourseId,
                    week: currentWeek,
                    attendance: attendanceData
                })
            });
            
            const result = await response.json();
            
            if (response.ok) {
                // تحديث زر الحفظ
                const saveBtn = document.querySelector('.btn-primary');
                if (saveBtn) {
                    saveBtn.innerHTML = '<i class="fas fa-check-circle"></i> تم الحفظ بنجاح';
                    saveBtn.style.background = '#10b981';
                    
                    setTimeout(() => {
                        saveBtn.innerHTML = '<i class="fas fa-save"></i> حفظ التعديلات';
                        saveBtn.style.background = '';
                    }, 2000);
                }
                
                // إعادة تعيين البيانات
                attendanceData = {};
                
                alert('تم حفظ الحضور بنجاح');
                
                // إعادة تحميل الصفحة لرؤية التغييرات
                setTimeout(() => {
                    location.reload();
                }, 1000);
            } else {
                alert('حدث خطأ: ' + (result.error || 'فشل في الحفظ'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال بالخادم');
        } finally {
            if (loadingIndicator) loadingIndicator.style.display = 'none';
        }
    }
    
    // تهيئة البيانات من الجدول
    document.addEventListener('DOMContentLoaded', function() {
        // تأكيد العنصر النشط في القائمة
        const menuLinks = document.querySelectorAll('.menu a');
        const currentPath = window.location.pathname;
        
        menuLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
        
        // جمع البيانات الحالية
        const cells = document.querySelectorAll('.attendance-cell[data-status]');
        cells.forEach(cell => {
            const status = cell.getAttribute('data-status');
            const studentId = cell.getAttribute('data-student');
            const day = cell.getAttribute('data-day');
            
            if (status && studentId && day && status !== 'null') {
                if (!attendanceData[studentId]) {
                    attendanceData[studentId] = {};
                }
                attendanceData[studentId][day] = status;
            }
        });
        
        // جعل الأعمدة متجاوبة مع التمرير
        const tableContainer = document.querySelector('.attendance-table-container');
        if (tableContainer) {
            tableContainer.addEventListener('scroll', function() {
                const scrollLeft = this.scrollLeft;
                const stickyCols = this.querySelectorAll('.student-col');
                stickyCols.forEach(col => {
                    col.style.transform = `translateX(${scrollLeft}px)`;
                });
            });
        }
    });
</script>
@endsection