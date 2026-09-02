<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'registration_no', 'phone'];
    public function enrollments() { return $this->hasMany(Enrollment::class); }
    public function assignments() { return $this->hasManyThrough(Assignment::class, Enrollment::class, 'student_id', 'course_id', 'id', 'course_id'); }
    public function submissions() { return $this->hasMany(Submission::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function courses() { return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')->withPivot(['status', 'enrollment_date']); }
}
