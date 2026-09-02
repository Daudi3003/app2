<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $fillable = ['user_id', 'phone', 'specialization'];
    public function courses() { return $this->hasMany(Course::class); }
    public function report() { return $this->hasMany(Report::class); }
    public function assignments() { return $this->hasMany(Assignment::class); }
    public function material() { return $this->hasMany(Material::class); }
    public function administrator() { return $this->belongsTo(Adminstrator::class, 'admin_id'); }
    public function Adminstrator() { return $this->administrator(); }
    public function users() { return $this->belongsTo(User::class, 'user_id'); }
    public function user() { return $this->users(); }
}
