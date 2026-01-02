<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard-courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/dashboard-courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/attendance', [AttendanceController::class, 'index'])
    ->middleware('auth')
    ->name('attendance.index');
    Route::get('/dashboard-teacher-report', [ReportController::class,'index'])->name('reports.index');
    Route::post('/dashboard-teacher-report', [ReportController::class,'store'])->name('reports.store');

    Route::patch('/reports/{report}/approve', [ReportController::class,'approve'])
        ->middleware('can-approve');

    Route::patch('/reports/{report}/reject', [ReportController::class,'reject'])
        ->middleware('can-approve');

    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');


});

require __DIR__.'/auth.php';
