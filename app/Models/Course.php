<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'course_name', 'summary', 'description', 'duration', 'status', 'category', 'level', 'admin_id', 'instructor_id',
    ];

    public function assignments() { return $this->hasMany(Assignment::class); }
    public function report() { return $this->hasMany(Report::class); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }
    public function lessons() { return $this->hasMany(Lesson::class); }
    public function instructor() { return $this->belongsTo(Instructor::class); }
    public function Adminstrator() { return $this->belongsTo(Adminstrator::class, 'admin_id'); }
    public function students() { return $this->belongsToMany(Student::class, 'enrollments', 'course_id', 'student_id')->withPivot(['status', 'enrollment_date']); }
}
