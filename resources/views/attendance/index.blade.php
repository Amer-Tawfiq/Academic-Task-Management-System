@extends('layouts.app')

@section('content')
<div style="direction: rtl; text-align: right; font-family: Arial, sans-serif;">
    <h2 style="text-align: center; margin-bottom: 20px;">جدول الحضور</h2>

    <!-- معلومات المقرر -->
    <form method="GET" style="display:flex; gap:10px; margin-bottom:15px; justify-content: flex-end;">
        <button style="padding: 8px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer;">
            تصفية
        </button>
        
        <input type="text" name="search" placeholder="بحث عن طالب" 
               style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 200px;">
        
        <select name="status" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            <option value="">الكل</option>
            <option value="present">حاضر</option>
            <option value="absent">غائب</option>
            <option value="excused">مستأذن</option>
        </select>
        
        <input type="number" name="week" min="1" value="{{ $week }}" placeholder="Week"
               style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 80px;">
        
        <select name="course_id" required style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 200px;">
            <option value="">اختر المقرر</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected($courseId==$course->id)>
                    {{ $course->course_name }}
                </option>
            @endforeach
        </select>
    </form>

    <!-- أزرار الأسابيع -->
    <div style="margin-bottom:20px; text-align: center;">
        @for($i=1; $i<=15; $i++)
            <a href="?course_id={{ $courseId }}&week={{ $i }}"
               style="display: inline-block; padding:8px 12px; margin:0 2px;
               background:{{ $week==$i ? '#0d47a1' : '#e5e7eb' }};
               color:{{ $week==$i ? 'white' : 'black' }};
               text-decoration:none; border-radius:5px; font-size: 14px;">
               Week {{ $i }}
            </a>
        @endfor
    </div>

    <!-- جدول الحضور - مطابق للصورة -->
    <table border="1" width="100%" cellpadding="8" style="border-collapse: collapse; text-align: center;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th style="padding: 10px; border: 1px solid #ddd; width: 15%;">طلاب</th>
                <th style="padding: 10px; border: 1px solid #ddd; width: 14.16%;">يوم السبت</th>
                <th style="padding: 10px; border: 1px solid #ddd; width: 14.16%;">يوم الأحد</th>
                <th style="padding: 10px; border: 1px solid #ddd; width: 14.16%;">يوم الاثنين</th>
                <th style="padding: 10px; border: 1px solid #ddd; width: 14.16%;">يوم الثلاثاء</th>
                <th style="padding: 10px; border: 1px solid #ddd; width: 14.16%;">يوم الأربعاء</th>
                <th style="padding: 10px; border: 1px solid #ddd; width: 14.16%;">يوم الخميس</th>
            </tr>
        </thead>
        <tbody>
            <!-- يجب توفير البيانات من الـ Controller -->
            @foreach($students as $student)
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">
                    {{ $student->name }}
                </td>
                <!-- يوم السبت -->
                @php
                    $dayStatus = $attendanceData[$student->id]['saturday'] ?? 'حاضر';
                    $bgColor = $dayStatus == 'حاضر' ? '#d4edda' : 
                              ($dayStatus == 'متأخر' ? '#fff3cd' : 
                              ($dayStatus == 'غائب' ? '#f8d7da' : '#d4edda'));
                    $textColor = $dayStatus == 'حاضر' ? '#155724' : 
                                ($dayStatus == 'متأخر' ? '#856404' : 
                                ($dayStatus == 'غائب' ? '#721c24' : '#155724'));
                @endphp
                <td style="padding: 10px; border: 1px solid #ddd; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                    {{ $dayStatus }}
                </td>
                
                <!-- يوم الأحد -->
                @php
                    $dayStatus = $attendanceData[$student->id]['sunday'] ?? 'حاضر';
                    $bgColor = $dayStatus == 'حاضر' ? '#d4edda' : 
                              ($dayStatus == 'متأخر' ? '#fff3cd' : 
                              ($dayStatus == 'غائب' ? '#f8d7da' : '#d4edda'));
                    $textColor = $dayStatus == 'حاضر' ? '#155724' : 
                                ($dayStatus == 'متأخر' ? '#856404' : 
                                ($dayStatus == 'غائب' ? '#721c24' : '#155724'));
                @endphp
                <td style="padding: 10px; border: 1px solid #ddd; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                    {{ $dayStatus }}
                </td>
                
                <!-- يوم الاثنين -->
                @php
                    $dayStatus = $attendanceData[$student->id]['monday'] ?? 'حاضر';
                    $bgColor = $dayStatus == 'حاضر' ? '#d4edda' : 
                              ($dayStatus == 'متأخر' ? '#fff3cd' : 
                              ($dayStatus == 'غائب' ? '#f8d7da' : '#d4edda'));
                    $textColor = $dayStatus == 'حاضر' ? '#155724' : 
                                ($dayStatus == 'متأخر' ? '#856404' : 
                                ($dayStatus == 'غائب' ? '#721c24' : '#155724'));
                @endphp
                <td style="padding: 10px; border: 1px solid #ddd; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                    {{ $dayStatus }}
                </td>
                
                <!-- يوم الثلاثاء -->
                @php
                    $dayStatus = $attendanceData[$student->id]['tuesday'] ?? 'حاضر';
                    $bgColor = $dayStatus == 'حاضر' ? '#d4edda' : 
                              ($dayStatus == 'متأخر' ? '#fff3cd' : 
                              ($dayStatus == 'غائب' ? '#f8d7da' : '#d4edda'));
                    $textColor = $dayStatus == 'حاضر' ? '#155724' : 
                                ($dayStatus == 'متأخر' ? '#856404' : 
                                ($dayStatus == 'غائب' ? '#721c24' : '#155724'));
                @endphp
                <td style="padding: 10px; border: 1px solid #ddd; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                    {{ $dayStatus }}
                </td>
                
                <!-- يوم الأربعاء -->
                @php
                    $dayStatus = $attendanceData[$student->id]['wednesday'] ?? 'حاضر';
                    $bgColor = $dayStatus == 'حاضر' ? '#d4edda' : 
                              ($dayStatus == 'متأخر' ? '#fff3cd' : 
                              ($dayStatus == 'غائب' ? '#f8d7da' : '#d4edda'));
                    $textColor = $dayStatus == 'حاضر' ? '#155724' : 
                                ($dayStatus == 'متأخر' ? '#856404' : 
                                ($dayStatus == 'غائب' ? '#721c24' : '#155724'));
                @endphp
                <td style="padding: 10px; border: 1px solid #ddd; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                    {{ $dayStatus }}
                </td>
                
                <!-- يوم الخميس -->
                @php
                    $dayStatus = $attendanceData[$student->id]['thursday'] ?? 'حاضر';
                    $bgColor = $dayStatus == 'حاضر' ? '#d4edda' : 
                              ($dayStatus == 'متأخر' ? '#fff3cd' : 
                              ($dayStatus == 'غائب' ? '#f8d7da' : '#d4edda'));
                    $textColor = $dayStatus == 'حاضر' ? '#155724' : 
                                ($dayStatus == 'متأخر' ? '#856404' : 
                                ($dayStatus == 'غائب' ? '#721c24' : '#155724'));
                @endphp
                <td style="padding: 10px; border: 1px solid #ddd; background-color: {{ $bgColor }}; color: {{ $textColor }};">
                    {{ $dayStatus }}
                </td>
            </tr>
            @endforeach
            
            <!-- الصف الأخير للطلب الإضافي كما في الصورة -->
            <tr>
                <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">
                    العدد من الطلاب......
                </td>
                <td style="padding: 10px; border: 1px solid #ddd;">......</td>
                <td style="padding: 10px; border: 1px solid #ddd;">......</td>
                <td style="padding: 10px; border: 1px solid #ddd;">......</td>
                <td style="padding: 10px; border: 1px solid #ddd;">......</td>
                <td style="padding: 10px; border: 1px solid #ddd;">......</td>
                <td style="padding: 10px; border: 1px solid #ddd;">هاشم الحاج</td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top:20px; text-align: center; color: green; font-weight: bold;">
        ✔ يمكن تقديم الحضور إلكترونيًا
    </p>
</div>

@endsection