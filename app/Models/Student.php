<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'name',
        'email',
        'phone',
        'major',
        'batch_year',
        'address',
        'gender',
        'date_of_birth'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'batch_year' => 'integer',
    ];

    //relationships
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'enrollments')
                    ->withPivot('enrollment_date', 'grade')
                    ->withTimestamps();
    }

    //access for full info
    public function scopeSearch($query, $term)
    {
        return $query->where('student_id', 'like', "%$term%")
                     ->orWhere('name', 'like', "%$term%")
                     ->orWhere('email', 'like', "%$term%")
                     ->orWhere('address', 'like', "%$term%")
                     ->orWhere('gender', 'like', "%$term%")
                     ->orWhere('date_of_birth', 'like', "%$term%");
    }
}
