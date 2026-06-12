<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();
        
        if ($request->has('search')) {
            $query->where('course_name', 'LIKE', "%{$request->search}%")
                  ->orWhere('course_code', 'LIKE', "%{$request->search}%");
        }
        
        if ($request->has('department') && $request->department != '') {
            $query->where('department', $request->department);
        }
        
        $courses = $query->latest()->paginate(10);
        $departments = Course::select('department')->distinct()->pluck('department');
        
        return view('courses.index', compact('courses', 'departments'));
    }
    
    public function create()
    {
        return view('courses.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|unique:courses|max:20',
            'course_name' => 'required|max:100',
            'credits' => 'required|integer|min:1|max:6',
            'department' => 'required|max:50',
            'instructor' => 'required|max:100',
            'description' => 'nullable',
            'capacity' => 'required|integer|min:1|max:200'
        ]);
        
        Course::create($validated);
        
        return redirect()->route('courses.index')
            ->with('success', 'Course created successfully!');
    }
    
    public function show(Course $course)
    {
        $course->load('students');
        return view('courses.show', compact('course'));
    }
    
    public function edit(Course $course)
    {
        return view('courses.edit', compact('course'));
    }
    
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_code' => ['required', 'max:20', Rule::unique('courses')->ignore($course->id)],
            'course_name' => 'required|max:100',
            'credits' => 'required|integer|min:1|max:6',
            'department' => 'required|max:50',
            'instructor' => 'required|max:100',
            'description' => 'nullable',
            'capacity' => 'required|integer|min:1|max:200'
        ]);
        
        $course->update($validated);
        
        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully!');
    }
    
    public function destroy(Course $course)
    {
        $course->delete();
        
        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }
}