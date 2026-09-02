<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['title', 'description', 'due_date', 'max_score', 'course_id', 'instructor_id'];
    protected $casts = ['due_date' => 'date'];
    public function instructor() { return $this->belongsTo(Instructor::class); }
    public function instructors() { return $this->instructor(); }
    public function course() { return $this->belongsTo(Course::class); }
    public function courses() { return $this->course(); }
    public function submissions() { return $this->hasMany(Submission::class); }
}
