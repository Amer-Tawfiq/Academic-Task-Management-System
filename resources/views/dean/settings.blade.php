@extends('layouts.dean')

@section('content')
<div class="settings-container">
    <div class="page-header">
        <h1>
            <i class="fas fa-cog"></i>
            الإعدادات الشخصية
        </h1>
        <p>إدارة ملفك الشخصي وإعدادات الحساب</p>
    </div>

    <div class="row">
        <!-- الملف الشخصي -->
        <div class="col-md-6">
            <div class="settings-card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>المعلومات الشخصية</h3>
                </div>
                <form action="{{ route('settings.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>الاسم الكامل</label>
                        <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                    </div>

                    <div class="form-group">
                        <label>البريد الإلكتروني</label>
                        <input type="email" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                    </div>

                    <div class="form-group">
                        <label>صورة الملف الشخصي</label>
                        <input type="file" name="avatar" class="form-control">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <i class="fas fa-save"></i>
                            حفظ التغييرات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- كلمة المرور -->
        <div class="col-md-6">
            <div class="settings-card">
                <div class="card-header danger">
                    <i class="fas fa-lock"></i>
                    <h3>تغيير كلمة المرور</h3>
                </div>
                <form action="{{ route('settings.password.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>كلمة المرور الحالية</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>كلمة المرور الجديدة</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>تأكيد كلمة المرور</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save btn-danger">
                            <i class="fas fa-key"></i>
                            تحديث كلمة المرور
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .settings-container {
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

    .page-header h1 i {
        color: #3b82f6;
    }

    .page-header p {
        color: #64748b;
        margin: 0;
    }

    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .col-md-6 {
        flex: 1;
        min-width: 300px;
    }

    .settings-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .card-header {
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header i {
        font-size: 20px;
        color: #3b82f6;
    }

    .card-header.danger i {
        color: #ef4444;
    }

    .card-header h3 {
        margin: 0;
        font-size: 18px;
        color: #334155;
    }

    .settings-card form {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #475569;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .form-actions {
        margin-top: 30px;
        text-align: left;
    }

    .btn-save {
        padding: 12px 24px;
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .btn-save.btn-danger {
        background: #ef4444;
    }

    .btn-save.btn-danger:hover {
        background: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
    }
</style>
@endsection
