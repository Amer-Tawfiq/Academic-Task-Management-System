<?php

use App\Http\Controllers\{
    ProfileController,
    TaskController,
    DashboardController,
    CourseController,
    AttendanceController,
    ReportController,
    TeacherController,
    NotificationController,
    SettingsController,
    DeanReportController
};
use App\Models\Task;
use App\Models\Course;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// ... inside the route ...
// I can't replace the whole file or huge chunks just to add an use statement at top and variable in middle.
// I will use multi_replace for this. This tool call is incorrect for non-contiguous changes.
// Retracting and using multi_replace.


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* ================= Profile ================= */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ================= Courses ================= */
    Route::get('/dashboard-courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/dashboard-courses', [CourseController::class, 'store'])->name('courses.store');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    /* ================= Attendance ================= */
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/teacher/attendance', [AttendanceController::class, 'index'])->name('teacher.attendance');
    Route::post('/teacher/attendance/store', [AttendanceController::class, 'store'])->name('teacher.attendance.store');

    /* ================= Reports (Teacher Reports) ================= */
    Route::get('/dashboard-teacher-report', [ReportController::class,'index'])->name('reports.index');
    Route::post('/dashboard-teacher-report', [ReportController::class,'store'])->name('reports.store');

    Route::patch('/reports/{report}/approve', [ReportController::class,'approve'])
        ->middleware('can-approve')
        ->name('reports.approve');

    Route::patch('/reports/{report}/reject', [ReportController::class,'reject'])
        ->middleware('can-approve')
        ->name('reports.reject');

    /* ================= Tasks ================= */
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/teacher', [TaskController::class, 'teacherTasks'])->name('teacher.tasks');
        Route::post('/', [TaskController::class, 'store'])->name('tasks.store');
        Route::patch('/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
        Route::put('/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    });

    /* ================= Teacher Profile ================= */
    Route::prefix('teacher-profile')
        ->name('teacher.')
        ->group(function () {
            Route::get('/', [TeacherController::class, 'profile'])->name('profile');
            Route::get('/edit', [TeacherController::class, 'edit'])->name('profile.edit');
            Route::put('/update', [TeacherController::class, 'update'])->name('profile.update');
            Route::get('/change-password', [TeacherController::class, 'showChangePasswordForm'])
                ->name('password.edit');
            Route::put('/update-password', [TeacherController::class, 'updatePassword'])
                ->name('password.update');
        });
    
    /* ================= Members (Teachers Management) ================= */
    Route::prefix('members')
        ->name('members.')
        ->group(function () {
            Route::get('/', [TeacherController::class, 'index'])->name('index');
            Route::post('/', [TeacherController::class, 'store'])->name('store');
            Route::get('/create', [TeacherController::class, 'create'])->name('create');
            Route::get('/{teacher}', [TeacherController::class, 'show'])->name('show');
            Route::get('/{teacher}/edit', [TeacherController::class, 'editMember'])->name('edit');
            Route::put('/{teacher}', [TeacherController::class, 'updateMember'])->name('update');
            Route::delete('/{teacher}', [TeacherController::class, 'destroy'])->name('destroy');
            Route::get('/{teacher}/details', [TeacherController::class, 'details'])->name('details');
        });

    /* ================= Notifications ================= */
    Route::prefix('notifications')
        ->name('notifications.')
        ->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::post('/', [NotificationController::class, 'store'])->name('store');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::get('/stats', [NotificationController::class, 'stats'])->name('stats');
        });

    /* ================= Settings ================= */
    Route::prefix('settings')
        ->name('settings.')
        ->middleware('auth')
        ->group(function () {
            Route::get('/', [SettingsController::class, 'index'])->name('index');
            Route::post('/profile', [SettingsController::class, 'updateProfile'])->name('profile.update');
            Route::post('/password', [SettingsController::class, 'updatePassword'])->name('password.update');
        });

    // ================= Dean & Head Routes =================
    
    /* ================= DEAN ROUTES (عميد) ================= */
    Route::middleware(['auth', 'role.name:dean'])
        ->prefix('dean')
        ->name('dean.')
        ->group(function () {
            // لوحة تحكم العميد
            Route::get('/dashboard', function () {
                return view('dean.dashboard');
            })->name('dashboard');
            
            // الدورات
            Route::get('/courses', function () {
                $courses = Course::with('teacher')->withCount('students')->get();
                return view('dean.courses', compact('courses'));
            })->name('courses');
            
            // المهام
            // Route::get('/tasks', function () {
            //      return view('tasks.index', [
            //         'userType' => 'dean',  // إضافة متغير للتمييز
            //         'pageTitle' => 'المهام - عميد الكلية'
            //     ]);
                
            // })->name('tasks');
             Route::get('/tasks', function () {
            // جلب المهام من قاعدة البيانات
            $tasks = Task::with(['assignedTo', 'department', 'createdBy'])
                        ->latest()
                        ->get();
            
            // جلب المقررات للفلترة
            $courses = Course::all();

            return view('tasks.index', [
                'tasks' => $tasks,
                'courses' => $courses,
                'userType' => 'dean',
                'pageTitle' => 'المهام - عميد الكلية',
                'layout' => 'layouts.app'
            ]);
        })->name('tasks');
            
            // الحضور
            Route::get('/attendance', function () {
                $departments = App\Models\Department::withCount('users')->get();
                $courses = App\Models\Course::select('id', 'course_name', 'department_id')->get();
                
                return view('dean.attendance', compact('departments', 'courses'));
            })->name('attendance');
            
            // تقارير العميد
            Route::prefix('reports')
                ->name('reports.')
                ->group(function () {
                    Route::get('/', [DeanReportController::class, 'index'])->name('index');
                    Route::get('/create', [DeanReportController::class, 'create'])->name('create');
                    Route::post('/', [DeanReportController::class, 'store'])->name('store');
                    Route::patch('/{report}/status', [DeanReportController::class, 'updateStatus'])->name('update-status');
                    Route::delete('/{report}', [DeanReportController::class, 'destroy'])->name('destroy');
                });
            
            // الإعدادات
            Route::get('/settings', function () {
                return view('dean.settings');
            })->name('settings');
        });

    /* ================= HEAD ROUTES (رئيس قسم) ================= */
    Route::middleware(['auth', 'role.name:head'])
        ->prefix('head')
        ->name('head.')
        ->group(function () {
            // لوحة تحكم رئيس القسم
            Route::get('/dashboard', function () {
                return view('head.dashboard');
            })->name('dashboard');
            
            // المقررات
            Route::get('/courses', [CourseController::class, 'index'])->name('courses');
            
            // المهام
            Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');
            
            // تقارير رئيس القسم
            Route::prefix('reports')
                ->name('reports.')
                ->group(function () {
                    Route::get('/', [DeanReportController::class, 'index'])->name('index');
                    Route::get('/create', [DeanReportController::class, 'create'])->name('create');
                    Route::post('/', [DeanReportController::class, 'store'])->name('store');
                    Route::patch('/{report}/status', [DeanReportController::class, 'updateStatus'])->name('update-status');
                    Route::delete('/{report}', [DeanReportController::class, 'destroy'])->name('destroy');
                });
            
            // الأعضاء
            Route::get('/members', [TeacherController::class, 'index'])->name('members.index');
            
            // الإشعارات
            Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
            
            // الإعدادات الشخصية
            Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        });

});

require __DIR__.'/auth.php';