<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = ['submission_file', 'submitted_at', 'score', 'feedback', 'status', 'student_id', 'assignment_id'];
    protected $casts = ['submitted_at' => 'datetime'];
    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function assignments() { return $this->assignment(); }
    public function student() { return $this->belongsTo(Student::class); }
    public function students() { return $this->student(); }
}
