<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = ['enrollment_date', 'status', 'student_id', 'course_id'];
    protected $casts = ['enrollment_date' => 'date'];
    public function course() { return $this->belongsTo(Course::class); }
    public function student() { return $this->belongsTo(Student::class); }
    public function courses() { return $this->course(); }
    public function students() { return $this->student(); }
}
