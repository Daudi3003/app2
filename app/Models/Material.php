<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'title',
        'material_type',
        'file_url',
        'description',
        'upload_at'
    ];

     public function instructors(){
        return $this->belongsTo(Instructor::class);
    }
    public function lessons(){
        return $this->belongsTo(Lesson::class);
    }

    public function students(){
        return $this->belongsTo(Student::class);
    }
}
