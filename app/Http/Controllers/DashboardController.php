<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        
        $recentStudents = Student::latest()->take(5)->get();
        $recentCourses = Course::latest()->take(5)->get();
        
        // Student by major statistics
        $studentsByMajor = Student::selectRaw('major, count(*) as count')
            ->groupBy('major')
            ->get();
        
        return view('dashboard', compact(
            'totalStudents', 'totalCourses', 'totalEnrollments',
            'recentStudents', 'recentCourses', 'studentsByMajor'
        ));
    }
}