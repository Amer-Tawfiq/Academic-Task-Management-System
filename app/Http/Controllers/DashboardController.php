<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $tasksCount = Task::count();
        $coursesCount = Course::count();

        $completedTasks = Task::where('status','completed')->count();
        $completionRate = $tasksCount > 0
            ? round(($completedTasks / $tasksCount) * 100)
            : 0;

        $latestTasks = Task::with('course')->latest()->take(5)->get();

        return view('dashboard', compact(
            'tasksCount',
            'coursesCount',
            'completionRate',
            'latestTasks'
        ));
    }
}
