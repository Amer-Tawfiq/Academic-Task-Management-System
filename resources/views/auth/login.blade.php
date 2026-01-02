<div style="display: flex; min-height: 100vh; font-family: sans-serif; direction: ltr;">
    <!-- الجزء الأيسر -->
    <div style="flex: 1; background-color: #0d47a1; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px;">
        <img src="{{ asset('images/logo.jpg') }}" alt="شعار" style="width: 120px; margin-bottom: 30px;">
        <h2 style="text-align: center; margin-bottom: 30px; font-size: 22px; font-weight: 600; line-height: 1.4;">
            نظام إدارة المهام<br>الأكاديمية
        </h2>
        <div style="margin-top: auto; text-align: center; font-size: 12px; padding-top: 20px;">
            <p>© 2025 نظام إدارة المهام الأكاديمية</p>
            <p style="margin-top: 5px;">نظام الإدارة</p>
        </div>
    </div>

    <!-- الجزء الأيمن -->
    <div style="flex: 1.5; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;">
        <form method="POST" action="{{ route('login') }}" style="width: 420px; background: white; padding: 45px 35px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
            @csrf
            <h3 style="text-align: center; margin-bottom: 35px; font-size: 24px; color: #333; font-weight: 700;">تسجيل الدخول</h3>

            <!-- البريد الإلكتروني -->
            <div style="margin-bottom: 20px;">
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">البريد الإلكتروني</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                    style="width: 100%; padding: 14px; border-radius: 6px; border: 1px solid #ddd; background-color: #f9f9f9; font-size: 15px;" 
                    placeholder="example@ust.edu">
                @error('email')
                    <p style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- كلمة المرور -->
            <div style="margin-bottom: 20px;">
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">كلمة المرور</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    style="width: 100%; padding: 14px; border-radius: 6px; border: 1px solid #ddd; background-color: #f9f9f9; font-size: 15px;">
                @error('password')
                    <p style="color: #dc3545; font-size: 14px; margin-top: 5px;">{{ $message }}</p>
                @enderror
            </div>

            <!-- اختيار الدور -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #555;">مشترك هيئة التدريس</label>
                <select name="role_id" style="width: 100%; padding: 14px; border-radius: 6px; border: 1px solid #ddd; background-color: #f9f9f9; font-size: 15px; color: #333;">
                    <option value="">اختر الدور</option>
                    <option value="1">معلم</option>
                    <option value="2">رئيس قسم</option>
                    <option value="3">عميد</option>
                </select>
            </div>

            <!-- زر تسجيل الدخول -->
            <button type="submit" style="width: 100%; background-color: #0d47a1; color: white; padding: 16px; border-radius: 6px; border: none; font-size: 16px; font-weight: 700; cursor: pointer; margin-top: 10px;">
                تسجيل الدخول
            </button>

            <!-- نسيت كلمة المرور -->
            @if (Route::has('password.request'))
                <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee;">
                    <a href="{{ route('password.request') }}" style="color: #0d47a1; text-decoration: none; font-size: 14px; font-weight: 600;">
                        هل نسيت كلمة المرور؟
                    </a>
                </div>
            @endif
        </form>
    </div>
</div>