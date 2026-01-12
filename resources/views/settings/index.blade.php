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
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
    }

    .settings-container {
        padding: 30px;
        font-family: 'Cairo', sans-serif;
        background: linear-gradient(135deg, #f6f8fc 0%, #eef2ff 100%);
        min-height: calc(100vh - 60px);
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        color: #1e293b;
        font-size: 28px;
        font-weight: 700;
        position: relative;
        padding-right: 15px;
        margin-bottom: 10px;
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
        font-weight: 500;
    }

    /* تخطيط الصفحة */
    .settings-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
        max-width: 1200px;
        margin: 0 auto;
    }

    /* القائمة الجانبية */
    .settings-sidebar {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        padding: 20px;
        height: fit-content;
    }

    .user-info {
        text-align: center;
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }

    .user-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid var(--primary-light);
        margin: 0 auto 15px;
        overflow: hidden;
        background: var(--light-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
        color: var(--primary-color);
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 5px;
    }

    .user-role {
        font-size: 14px;
        color: var(--primary-color);
        font-weight: 600;
        background: var(--primary-light);
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-block;
    }

    .settings-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .settings-menu li {
        margin-bottom: 5px;
    }

    .settings-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        color: var(--dark-color);
        text-decoration: none;
        border-radius: var(--radius-sm);
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .settings-menu a:hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }

    .settings-menu a.active {
        background: var(--primary-light);
        color: var(--primary-color);
        border-right: 4px solid var(--primary-color);
    }

    .settings-menu a i {
        width: 20px;
        font-size: 16px;
    }

    /* المحتوى الرئيسي */
    .settings-content {
        background: white;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        padding: 30px;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .tab-header {
        margin-bottom: 30px;
    }

    .tab-title {
        color: var(--dark-color);
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tab-title i {
        color: var(--primary-color);
    }

    .tab-description {
        color: #64748b;
        font-size: 14px;
    }

    /* النماذج */
    .form-group {
        margin-bottom: 25px;
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

    .form-hint {
        font-size: 12px;
        color: var(--gray-color);
        margin-top: 5px;
        display: block;
    }

    /* رفع الصورة */
    .image-upload-container {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .image-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 3px solid var(--border-color);
        overflow: hidden;
        background: var(--light-color);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .image-preview:hover {
        border-color: var(--primary-color);
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview i {
        font-size: 40px;
        color: var(--gray-color);
    }

    .image-upload-info {
        flex: 1;
    }

    /* الأزرار */
    .btn {
        padding: 12px 24px;
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

    .form-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
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

    .alert-danger {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
        border-right: 4px solid var(--danger-color);
    }

    .alert-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border-right: 4px solid var(--warning-color);
    }

    .alert i {
        font-size: 18px;
    }

    /* أخطاء النماذج */
    .error-message {
        color: var(--danger-color);
        font-size: 12px;
        margin-top: 5px;
        display: block;
    }

    /* معلومات النظام */
    .system-info {
        background: #f8fafc;
        border-radius: var(--radius-sm);
        padding: 20px;
        margin-top: 30px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .info-item i {
        color: var(--primary-color);
        font-size: 18px;
    }

    .info-label {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 13px;
    }

    .info-value {
        color: #64748b;
        font-size: 13px;
        margin-top: 2px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .settings-container {
            padding: 15px;
        }
        
        .settings-layout {
            grid-template-columns: 1fr;
        }
        
        .settings-sidebar {
            margin-bottom: 20px;
        }
        
        .settings-content {
            padding: 20px;
        }
        
        .image-upload-container {
            flex-direction: column;
            text-align: center;
        }
        
        .form-actions {
            flex-direction: column;
            gap: 10px;
        }
        
        .form-actions button {
            width: 100%;
        }
    }

    /* التأكد من كلمة المرور */
    .password-toggle {
        position: relative;
    }

    .password-toggle-btn {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray-color);
        cursor: pointer;
        padding: 5px;
    }
</style>

<div class="settings-container">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h1 class="page-title">الإعدادات الشخصية</h1>
        <div class="page-subtitle">إدارة ملفك الشخصي وإعدادات الحساب</div>
    </div>

    <!-- رسائل التنبيه -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>يوجد أخطاء في النموذج:</strong>
                <ul style="margin: 10px 0 0 20px; font-size: 14px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- المحتوى الرئيسي -->
    <div class="settings-layout">
        <!-- القائمة الجانبية -->
        <div class="settings-sidebar">
            <!-- معلومات المستخدم -->
            <div class="user-info">
                <div class="user-avatar" onclick="document.getElementById('profileImage').click()">
                    @if($user->image)
                        <img src="{{ asset('images/' . $user->image) }}" alt="{{ $user->name }}" 
                             onerror="this.src='{{ asset('images/avatar.png') }}'">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                </div>
                
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-role">
                    @php
                        $roleName = $user->role->role_name ?? 'مستخدم';
                        $roleTranslations = [
                            'Doctor' => 'مدرس',
                            'Head' => 'رئيس قسم',
                            'Dean' => 'مدير'
                        ];
                        echo $roleTranslations[$roleName] ?? $roleName;
                    @endphp
                </div>
            </div>

            <!-- القائمة -->
            <ul class="settings-menu">
                <li>
                    <a href="#profile" class="tab-link active" data-tab="profile">
                        <i class="fas fa-user-circle"></i>
                        <span>الملف الشخصي</span>
                    </a>
                </li>
                <li>
                    <a href="#password" class="tab-link" data-tab="password">
                        <i class="fas fa-lock"></i>
                        <span>كلمة المرور</span>
                    </a>
                </li>
                <li>
                    <a href="#account" class="tab-link" data-tab="account">
                        <i class="fas fa-cog"></i>
                        <span>معلومات الحساب</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- المحتوى -->
        <div class="settings-content">
            <!-- تبويب الملف الشخصي -->
            <div id="profile-tab" class="tab-content {{ session('activeTab', 'profile') === 'profile' ? 'active' : '' }}">
                <div class="tab-header">
                    <h2 class="tab-title">
                        <i class="fas fa-user-circle"></i>
                        الملف الشخصي
                    </h2>
                    <p class="tab-description">تحديث معلوماتك الشخصية والصورة</p>
                </div>

                <form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    
                    <!-- حقل الصورة المخفي -->
                    <input type="file" id="profileImage" name="image" accept="image/*" style="display: none;" onchange="previewProfileImage(this)">
                    
                    <!-- رفع الصورة -->
                    <div class="image-upload-container">
                        <div class="image-preview" onclick="document.getElementById('profileImage').click()">
                            @if($user->image)
                                <img src="{{ asset('images/' . $user->image) }}" alt="{{ $user->name }}" 
                                     onerror="this.src='{{ asset('images/avatar.png') }}'">
                            @else
                                <i class="fas fa-camera"></i>
                            @endif
                        </div>
                        
                        <div class="image-upload-info">
                            <p style="font-weight: 600; color: var(--dark-color); margin-bottom: 5px;">الصورة الشخصية</p>
                            <p style="color: var(--gray-color); font-size: 13px; margin-bottom: 10px;">
                                انقر على الصورة لتغييرها. المسموح: JPG, PNG, GIF. الحجم الأقصى: 2MB
                            </p>
                            <button type="button" class="btn" onclick="document.getElementById('profileImage').click()"
                                    style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                                <i class="fas fa-upload"></i> تغيير الصورة
                            </button>
                        </div>
                    </div>
                    
                    <!-- الاسم -->
                    <div class="form-group">
                        <label class="form-label required">الاسم الكامل</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               placeholder="أدخل الاسم الكامل" class="form-input" required>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- البريد الإلكتروني -->
                    <div class="form-group">
                        <label class="form-label required">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               placeholder="example@domain.com" class="form-input" required>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- معلومات إضافية بناءً على نوع المستخدم -->
                    @if($user->role)
                        <div class="form-group">
                            <label class="form-label">الوظيفة</label>
                            <input type="text" value="{{ $user->role->role_name }}" class="form-input" disabled>
                        </div>
                    @endif
                    
                    @if($user->department)
                        <div class="form-group">
                            <label class="form-label">القسم</label>
                            <input type="text" value="{{ $user->department->name }}" class="form-input" disabled>
                        </div>
                    @endif
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>

            <!-- تبويب كلمة المرور -->
            <div id="password-tab" class="tab-content {{ session('activeTab') === 'password' ? 'active' : '' }}">
                <div class="tab-header">
                    <h2 class="tab-title">
                        <i class="fas fa-lock"></i>
                        كلمة المرور
                    </h2>
                    <p class="tab-description">تغيير كلمة مرور حسابك</p>
                </div>

                <form method="POST" action="{{ route('settings.password.update') }}" id="passwordForm">
                    @csrf
                    
                    <!-- كلمة المرور الحالية -->
                    <div class="form-group">
                        <label class="form-label required">كلمة المرور الحالية</label>
                        <div class="password-toggle">
                            <input type="password" name="current_password" placeholder="أدخل كلمة المرور الحالية" 
                                   class="form-input" required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword(this, 'current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- كلمة المرور الجديدة -->
                    <div class="form-group">
                        <label class="form-label required">كلمة المرور الجديدة</label>
                        <div class="password-toggle">
                            <input type="password" name="password" placeholder="أدخل كلمة المرور الجديدة" 
                                   class="form-input" required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword(this, 'password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="form-hint">يجب أن تحتوي على 8 أحرف على الأقل</span>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <!-- تأكيد كلمة المرور -->
                    <div class="form-group">
                        <label class="form-label required">تأكيد كلمة المرور الجديدة</label>
                        <div class="password-toggle">
                            <input type="password" name="password_confirmation" placeholder="أعد إدخال كلمة المرور الجديدة" 
                                   class="form-input" required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword(this, 'password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-key"></i>
                            تغيير كلمة المرور
                        </button>
                    </div>
                </form>
            </div>

            <!-- تبويب معلومات الحساب -->
            <div id="account-tab" class="tab-content {{ session('activeTab') === 'account' ? 'active' : '' }}">
                <div class="tab-header">
                    <h2 class="tab-title">
                        <i class="fas fa-cog"></i>
                        معلومات الحساب
                    </h2>
                    <p class="tab-description">معلومات حول حسابك وإعداداته</p>
                </div>

                <!-- معلومات النظام -->
                <div class="system-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="fas fa-user-tag"></i>
                            <div>
                                <div class="info-label">دور المستخدم</div>
                                <div class="info-value">
                                    @php
                                        $roleTranslations = [
                                            'Doctor' => 'مدرس',
                                            'Head' => 'رئيس قسم',
                                            'Dean' => 'مدير'
                                        ];
                                        echo $roleTranslations[$user->role->role_name ?? ''] ?? $user->role->role_name ?? 'غير محدد';
                                    @endphp
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-building"></i>
                            <div>
                                <div class="info-label">القسم</div>
                                <div class="info-value">{{ $user->department->name ?? 'غير محدد' }}</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <div class="info-label">تاريخ الإنشاء</div>
                                <div class="info-value">{{ $user->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <div class="info-label">آخر تحديث</div>
                                <div class="info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <div class="info-label">حالة البريد</div>
                                <div class="info-value">
                                    @if($user->email_verified_at)
                                        <span style="color: var(--success-color);">✓ تم التحقق</span>
                                    @else
                                        <span style="color: var(--warning-color);">✗ لم يتم التحقق</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <i class="fas fa-id-card"></i>
                            <div>
                                <div class="info-label">معرف المستخدم</div>
                                <div class="info-value">#{{ $user->id }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- معلومات الحساب حسب نوع المستخدم -->
                @if($userType === 'teacher')
                <div style="background: #f0f9ff; border-radius: var(--radius-sm); padding: 20px; margin-top: 20px;">
                    <h3 style="color: var(--dark-color); margin-bottom: 15px; font-size: 16px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-chalkboard-teacher"></i>
                        معلومات المدرس
                    </h3>
                    <p style="color: var(--gray-color); font-size: 14px; margin-bottom: 15px;">
                        أنت مسجل كمدرس في النظام. يمكنك إدارة المقررات والمهام الخاصة بك.
                    </p>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('tasks.index') }}" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                            <i class="fas fa-tasks"></i> المهام
                        </a>
                        @if(isset($teacherRole))
                            <a href="{{ route('members.index') }}" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                                <i class="fas fa-users"></i> المدرسين
                            </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($userType === 'head_of_department')
                <div style="background: #f0f9ff; border-radius: var(--radius-sm); padding: 20px; margin-top: 20px;">
                    <h3 style="color: var(--dark-color); margin-bottom: 15px; font-size: 16px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-user-tie"></i>
                        معلومات رئيس القسم
                    </h3>
                    <p style="color: var(--gray-color); font-size: 14px; margin-bottom: 15px;">
                        أنت مسجل كرئيس قسم. يمكنك إدارة المقررات والمدرسين والمهام في قسمك.
                    </p>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="/dashboard-courses" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                            <i class="fas fa-book"></i> المقررات
                        </a>
                        <a href="{{ route('tasks.index') }}" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                            <i class="fas fa-tasks"></i> المهام
                        </a>
                        @if(isset($teacherRole))
                            <a href="{{ route('members.index') }}" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                                <i class="fas fa-users"></i> المدرسين
                            </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($userType === 'dean')
                <div style="background: #f0f9ff; border-radius: var(--radius-sm); padding: 20px; margin-top: 20px;">
                    <h3 style="color: var(--dark-color); margin-bottom: 15px; font-size: 16px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-user-shield"></i>
                        معلومات المدير
                    </h3>
                    <p style="color: var(--gray-color); font-size: 14px; margin-bottom: 15px;">
                        أنت مسجل كمدير للنظام. لديك صلاحيات كاملة لإدارة جميع جوانب النظام.
                    </p>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="{{ route('dashboard-courses') }}" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                            <i class="fas fa-book"></i> المقررات
                        </a>
                        <a href="{{ route('tasks.index') }}" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                            <i class="fas fa-tasks"></i> المهام
                        </a>
                        <a href="{{ route('members.index') }}" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                            <i class="fas fa-users"></i> المدرسين
                        </a>
                        <a href="{{ route('reports') }}" class="btn" style="background: var(--primary-light); color: var(--primary-color); padding: 8px 16px; font-size: 13px;">
                            <i class="fas fa-chart-bar"></i> التقارير
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // التحكم في التبويبات
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');
        
        // تفعيل التبويب الأول تلقائياً إذا لم يكن هناك تبويب نشط
        let hasActiveTab = false;
        tabContents.forEach(tab => {
            if (tab.classList.contains('active')) {
                hasActiveTab = true;
            }
        });
        
        if (!hasActiveTab && tabContents.length > 0) {
            tabContents[0].classList.add('active');
            tabLinks[0].classList.add('active');
        }
        
        // إضافة حدث النقر على روابط التبويبات
        tabLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const tabId = this.getAttribute('data-tab');
                
                // إزالة النشط من جميع التبويبات
                tabLinks.forEach(l => l.classList.remove('active'));
                tabContents.forEach(t => t.classList.remove('active'));
                
                // إضافة النشط للتبويب المحدد
                this.classList.add('active');
                document.getElementById(tabId + '-tab').classList.add('active');
                
                // حفظ التبويب النشط في localStorage
                localStorage.setItem('activeTab', tabId);
            });
        });
        
        // استعادة التبويب النشط من localStorage
        const savedTab = localStorage.getItem('activeTab');
        if (savedTab) {
            const savedLink = document.querySelector(`.tab-link[data-tab="${savedTab}"]`);
            if (savedLink) {
                savedLink.click();
            }
        }
    });

    // معاينة الصورة
    function previewProfileImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.image-preview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // إظهار/إخفاء كلمة المرور
    function togglePassword(button, fieldName) {
        const input = document.querySelector(`input[name="${fieldName}"]`);
        const icon = button.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // التحقق من صحة النماذج قبل الإرسال
    document.getElementById('profileForm')?.addEventListener('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            this.classList.add('was-validated');
        }
    });

    document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
        const password = document.querySelector('input[name="password"]').value;
        const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            alert('كلمة المرور وتأكيدها غير متطابقين');
        }
    });
</script>
@endsection