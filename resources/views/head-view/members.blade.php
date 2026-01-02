@extends('layouts.app')

@section('content')
<div class="members-header" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
                <i class="fas fa-users" style="color: #22d3ee; margin-left: 10px;"></i>
                قائمة الأساتذة
            </h2>
            <p style="color: #64748b; font-size: 16px;">
                إدارة وتتبع جميع أعضاء هيئة التدريس في القسم
            </p>
        </div>
        <button style="background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-user-plus"></i>
            إضافة أستاذ جديد
        </button>
    </div>
</div>

<!-- إحصائيات الأساتذة -->
<div class="stats-cards" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="stat-card" style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chalkboard-teacher" style="color: white; font-size: 22px;"></i>
            </div>
            <div>
                <div style="font-size: 14px; color: #64748b;">إجمالي الأساتذة</div>
                <div style="font-size: 32px; font-weight: 700; color: #0f172a;">{{ $totalTeachers ?? '24' }}</div>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #10b981 0%, #047857 100%); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-user-check" style="color: white; font-size: 22px;"></i>
            </div>
            <div>
                <div style="font-size: 14px; color: #64748b;">الحاضرين اليوم</div>
                <div style="font-size: 32px; font-weight: 700; color: #0f172a;">{{ $presentToday ?? '18' }}</div>
            </div>
        </div>
    </div>
    
    <div class="stat-card" style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-book-open" style="color: white; font-size: 22px;"></i>
            </div>
            <div>
                <div style="font-size: 14px; color: #64748b;">متوسط المقررات</div>
                <div style="font-size: 32px; font-weight: 700; color: #0f172a;">{{ $avgCourses ?? '3.2' }}</div>
            </div>
        </div>
    </div>
</div>

<!-- محرك البحث والتصفية -->
<div class="search-filter" style="background: white; border-radius: 16px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0; margin-bottom: 30px;">
    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <div style="position: relative;">
                <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                <input type="text" placeholder="ابحث عن أستاذ بالاسم أو الرقم الجامعي..." style="width: 100%; padding: 12px 45px 12px 15px; border: 1px solid #e2e8f0; border-radius: 10px; font-family: 'Cairo', sans-serif; font-size: 14px;">
            </div>
        </div>
        
        <select style="padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 10px; background: white; font-family: 'Cairo', sans-serif; font-size: 14px; min-width: 180px;">
            <option value="">جميع التخصصات</option>
            <option value="cs">علوم الحاسب</option>
            <option value="math">الرياضيات</option>
            <option value="physics">الفيزياء</option>
            <option value="chemistry">الكيمياء</option>
        </select>
        
        <select style="padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 10px; background: white; font-family: 'Cairo', sans-serif; font-size: 14px; min-width: 180px;">
            <option value="">جميع الرتب العلمية</option>
            <option value="professor">أستاذ</option>
            <option value="associate">أستاذ مشارك</option>
            <option value="assistant">أستاذ مساعد</option>
            <option value="lecturer">محاضر</option>
        </select>
        
        <button style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 12px 20px; border-radius: 10px; cursor: pointer; display: flex; align-items: center; gap: 8px;">
            <i class="fas fa-filter"></i>
            تصفية
        </button>
    </div>
</div>

<!-- جدول الأساتذة -->
<div class="table-container" style="background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h4 style="font-size: 18px; font-weight: 600; color: #0f172a;">قائمة الأساتذة</h4>
        <div style="display: flex; gap: 10px;">
            <button style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 15px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-download"></i>
                تصدير
            </button>
            <button style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 15px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-print"></i>
                طباعة
            </button>
        </div>
    </div>
    
    @if(isset($teachers) && count($teachers) > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="background: #f8fafc; padding: 15px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">#</th>
                    <th style="background: #f8fafc; padding: 15px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">الأستاذ</th>
                    <th style="background: #f8fafc; padding: 15px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">الرقم الجامعي</th>
                    <th style="background: #f8fafc; padding: 15px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">التخصص</th>
                    <th style="background: #f8fafc; padding: 15px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">الرتبة العلمية</th>
                    <th style="background: #f8fafc; padding: 15px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">عدد المقررات</th>
                    <th style="background: #f8fafc; padding: 15px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">الحالة</th>
                    <th style="background: #f8fafc; padding: 15px; text-align: right; font-weight: 600; color: #475569; border-bottom: 2px solid #e2e8f0;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $teacher)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 15px; color: #334155;">{{ $loop->iteration }}</td>
                    <td style="padding: 15px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                                {{ substr($teacher->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 600; color: #0f172a;">{{ $teacher->name }}</div>
                                <div style="font-size: 13px; color: #64748b;">{{ $teacher->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 15px; color: #334155;">{{ $teacher->university_id ?? 'T-00'.$loop->iteration }}</td>
                    <td style="padding: 15px; color: #334155;">{{ $teacher->specialization ?? 'علوم الحاسب' }}</td>
                    <td style="padding: 15px;">
                        @php
                            $rankColors = [
                                'أستاذ' => 'bg-purple-100 text-purple-800',
                                'أستاذ مشارك' => 'bg-blue-100 text-blue-800',
                                'أستاذ مساعد' => 'bg-green-100 text-green-800',
                                'محاضر' => 'bg-yellow-100 text-yellow-800'
                            ];
                            $rank = $teacher->rank ?? 'أستاذ مساعد';
                            $rankColor = $rankColors[$rank] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span style="padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; {{ 
                            $rank == 'أستاذ' ? 'background: #ede9fe; color: #5b21b6;' :
                            ($rank == 'أستاذ مشارك' ? 'background: #dbeafe; color: #1e40af;' :
                            ($rank == 'أستاذ مساعد' ? 'background: #dcfce7; color: #166534;' :
                            'background: #fef3c7; color: #92400e;'))
                        }}">
                            {{ $rank }}
                        </span>
                    </td>
                    <td style="padding: 15px; color: #334155;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span style="font-weight: 600;">{{ $teacher->courses_count ?? rand(2, 5) }}</span>
                            <span style="font-size: 12px; color: #64748b;">مقرر</span>
                        </div>
                    </td>
                    <td style="padding: 15px;">
                        @php
                            $status = $teacher->status ?? (rand(0, 1) ? 'نشط' : 'إجازة');
                        @endphp
                        <span style="padding: 5px 12px; border-radius: 20px; font-size: 13px; font-weight: 500; {{ 
                            $status == 'نشط' ? 'background: #dcfce7; color: #166534;' :
                            ($status == 'إجازة' ? 'background: #fef3c7; color: #92400e;' :
                            'background: #fee2e2; color: #991b1b;')
                        }}">
                            <i class="fas fa-circle" style="font-size: 8px; margin-left: 5px;"></i>
                            {{ $status }}
                        </span>
                    </td>
                    <td style="padding: 15px;">
                        <div style="display: flex; gap: 8px;">
                            <button title="عرض الملف الشخصي" style="background: #dbeafe; color: #1e40af; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button title="تعديل البيانات" style="background: #fef3c7; color: #92400e; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button title="إرسال رسالة" style="background: #dcfce7; color: #166534; border: none; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- الترقيم الصفحي -->
    <div style="display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 30px;">
        <button style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; cursor: pointer;">
            <i class="fas fa-chevron-right"></i>
        </button>
        <button style="background: #3b82f6; color: white; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer;">1</button>
        <button style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; cursor: pointer;">2</button>
        <button style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; cursor: pointer;">3</button>
        <span style="color: #64748b;">...</span>
        <button style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; cursor: pointer;">10</button>
        <button style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 12px; border-radius: 8px; cursor: pointer;">
            <i class="fas fa-chevron-left"></i>
        </button>
    </div>
    @else
    <div style="text-align: center; padding: 60px 20px; color: #64748b;">
        <i class="fas fa-users" style="font-size: 64px; color: #e2e8f0; margin-bottom: 20px;"></i>
        <h4 style="font-size: 18px; font-weight: 600; margin-bottom: 10px;">لا توجد بيانات للأساتذة</h4>
        <p style="margin-bottom: 20px;">لم يتم إضافة أي أساتذة إلى النظام بعد</p>
        <button style="background: #3b82f6; color: white; border: none