<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['title', 'description', 'content', 'lesson_order', 'course_id'];
    public function course() { return $this->belongsTo(Course::class); }
    public function courses() { return $this->course(); }
    public function material() { return $this->hasMany(Material::class); }
}
