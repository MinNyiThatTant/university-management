<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'course_name',
        'credits',
        'department',
        'instructor',
        'description',
        'capacity',
    ];

    //relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'enrollments')
                    ->withPivot('enrollment_date', 'status', 'grade')
                    ->withTimestamps();
    }

    //check available slots
    public function availableSlots()
    {
        $enrolledCount = $this->enrollments()->where('status', 'active')->count();
        return max(0, $this->capacity - $enrolledCount);
    }

    public function isFull()
    {
        return $this->availableSlots() <= 0;
    }
}
