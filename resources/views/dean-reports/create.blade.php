{{-- resources/views/dean-reports/create.blade.php --}}
@extends($layout ?? 'layouts.app')

@section('content')
<div class="modern-report-container">
    <!-- Custom Styles for this page -->
    <style>
        .modern-report-container {
            direction: rtl;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            font-family: 'Cairo', sans-serif;
        }
        
        .report-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: slideUp 0.5s ease-out;
        }
        
        .report-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .header-content {
            position: relative;
            z-index: 2;
        }
        
        .header-icon {
            font-size: 32px;
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            backdrop-filter: blur(5px);
        }
        
        .header-title {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        
        .header-subtitle {
            opacity: 0.9;
            margin-top: 5px;
            font-size: 14px;
        }
        
        /* Decoration circles */
        .decoration-circle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        .circle-1 { width: 150px; height: 150px; top: -50px; right: -50px; }
        .circle-2 { width: 100px; height: 100px; bottom: -30px; left: 20px; }
        
        .card-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #1e293b;
            font-size: 15px;
        }
        
        .form-control, .form-select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            background-color: #f8fafc;
            font-family: inherit;
            font-size: 15px;
            color: #334155;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            outline: none;
        }
        
        .form-text {
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 16px 32px;
            border: none;
            border-radius: 16px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3);
        }
        
        .btn-cancel {
            background: #f1f5f9;
            color: #64748b;
            padding: 16px 32px;
            border: none;
            border-radius: 16px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-cancel:hover {
            background: #e2e8f0;
            color: #475569;
        }
        
        .actions-row {
            display: flex;
            gap: 15px;
            margin-top: 40px;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* File Upload Styling */
        .file-upload-wrapper {
            position: relative;
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        
        .file-upload-wrapper:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        
        .file-upload-icon {
            font-size: 32px;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .file-upload-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
    </style>

    <div class="report-card">
        <div class="report-header">
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            
            <div class="header-content">
                <div class="header-icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h1 class="header-title">إضافة تقرير جديد</h1>
                <p class="header-subtitle">قم بتعبئة البيانات أدناه لإرسال تقرير جديد للنظام</p>
            </div>
        </div>

        <div class="card-body">
            @php
                $userRole = auth()->user()->role->role_name;
                $storeRoute = $userRole == 'Dean' ? 'dean.reports.store' : 'head.reports.store';
                $indexRoute = $userRole == 'Dean' ? 'dean.reports.index' : 'head.reports.index';
            @endphp

            <form action="{{ route($storeRoute) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
                    <!-- نوع التقرير + التاريخ -->
                    <div class="col-md-6" style="flex: 1; min-width: 300px; padding: 0 10px;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-filter" style="color: #3b82f6; margin-left: 8px;"></i>
                                نوع التقرير <span style="color: #ef4444">*</span>
                            </label>
                            <select name="report_type" class="form-select" required>
                                <option value="">اختر نوع التقرير...</option>
                                @foreach(App\Models\DeanReport::getReportTypes() as $key => $value)
                                    <option value="{{ $key }}" {{ old('report_type') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6" style="flex: 1; min-width: 300px; padding: 0 10px;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="far fa-calendar-alt" style="color: #f59e0b; margin-left: 8px;"></i>
                                تاريخ التقرير <span style="color: #ef4444">*</span>
                            </label>
                            <input type="date" name="report_date" 
                                   class="form-control" 
                                   value="{{ old('report_date', date('Y-m-d')) }}" 
                                   required>
                        </div>
                    </div>

                    <!-- المستخدم والحالة -->
                    <div class="col-md-6" style="flex: 1; min-width: 300px; padding: 0 10px;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-user-tag" style="color: #10b981; margin-left: 8px;"></i>
                                {{ $userRole == 'Head' ? 'المعلم المعني' : 'المستخدم المعني' }} <span style="color: #ef4444">*</span>
                            </label>
                            <select name="user_id" class="form-select" required>
                                <option value="">اختر {{ $userRole == 'Head' ? 'المعلم' : 'المستخدم' }}...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} 
                                        <span style="font-size: 0.9em; opacity: 0.7;">({{ $user->email }})</span>
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <i class="fas fa-info-circle"></i>
                                يتم عرض {{ $userRole == 'Head' ? 'معلمي قسمك' : 'مستخدمي الكلية' }} فقط
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6" style="flex: 1; min-width: 300px; padding: 0 10px;">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-info-circle" style="color: #8b5cf6; margin-left: 8px;"></i>
                                حالة التقرير <span style="color: #ef4444">*</span>
                            </label>
                            <select name="status" class="form-select" required>
                                @foreach(App\Models\DeanReport::getStatuses() as $key => $value)
                                    <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- الملاحظات -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-align-right" style="color: #64748b; margin-left: 8px;"></i>
                        تفاصيل وملاحظات
                    </label>
                    <textarea name="notes" class="form-control" rows="4" placeholder="اكتب أي ملاحظات أو تفاصيل إضافية هنا...">{{ old('notes') }}</textarea>
                </div>

                <!-- رفع الملف -->
                <div class="form-group">
                    <label class="form-label">ملفات مرفقة</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="file" class="file-upload-input" accept=".pdf,.doc,.docx,.xls,.xlsx" onchange="updateFileName(this)">
                        <div class="file-upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h4 style="font-size: 16px; margin-bottom: 5px; color: #334155;">انقر لرفع ملف أو اسحبه هنا</h4>
                        <p style="color: #94a3b8; font-size: 13px; margin: 0;" id="fileNameDisplay">
                            PDF, Word, Excel (Max: 10MB)
                        </p>
                    </div>
                    @error('file')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- أزرار التحكم -->
                <div class="actions-row">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i>
                        حفظ وإرسال التقرير
                    </button>
                    
                    <a href="{{ route($indexRoute) }}" class="btn-cancel">
                        <i class="fas fa-times"></i>
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateFileName(input) {
        const display = document.getElementById('fileNameDisplay');
        if (input.files && input.files[0]) {
            display.textContent = 'تم اختيار: ' + input.files[0].name;
            display.style.color = '#10b981';
            display.style.fontWeight = 'bold';
        } else {
            display.textContent = 'PDF, Word, Excel (Max: 10MB)';
            display.style.color = '#94a3b8';
            display.style.fontWeight = 'normal';
        }
    }
</script>
@endsection