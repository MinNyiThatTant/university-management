<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    // Display listing of students
    public function index(Request $request)
    {
        $query = Student::query();
        
        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }
        
        // Filter by major
        if ($request->has('major') && $request->major != '') {
            $query->where('major', $request->major);
        }
        
        $students = $query->latest()->paginate(10);
        $majors = Student::select('major')->distinct()->pluck('major');
        
        return view('students.index', compact('students', 'majors'));
    }
    
    // Show create form
    public function create()
    {
        return view('students.create');
    }
    
    // Store new student
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|unique:students|max:20',
            'name' => 'required|max:100',
            'email' => 'required|email|unique:students',
            'phone' => 'nullable|max:15',
            'major' => 'required|max:50',
            'batch_year' => 'required|integer|min:2000|max:' . date('Y'),
            'address' => 'nullable',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today'
        ]);
        
        Student::create($validated);
        
        return redirect()->route('students.index')
            ->with('success', 'Student created successfully!');
    }
    
    // Show single student
    public function show(Student $student)
    {
        $student->load('courses');
        return view('students.show', compact('student'));
    }
    
    // Show edit form
    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }
    
    // Update student
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'max:20', Rule::unique('students')->ignore($student->id)],
            'name' => 'required|max:100',
            'email' => ['required', 'email', Rule::unique('students')->ignore($student->id)],
            'phone' => 'nullable|max:15',
            'major' => 'required|max:50',
            'batch_year' => 'required|integer|min:2000|max:' . date('Y'),
            'address' => 'nullable',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today'
        ]);
        
        $student->update($validated);
        
        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully!');
    }
    
    // Delete student
    public function destroy(Student $student)
    {
        $student->delete();
        
        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully!');
    }
}