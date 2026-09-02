<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adminstrator extends Model
{
    protected $fillable = ['user_id', 'phone'];
    public function instructors() { return $this->hasMany(Instructor::class); }
    public function courses() { return $this->hasMany(Course::class, 'admin_id'); }
    public function students() { return $this->hasMany(Student::class); }
    public function users() { return $this->belongsTo(User::class, 'user_id'); }
    public function user() { return $this->users(); }
}
