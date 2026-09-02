<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
  
    protected $fillable = [
        'title',
        'description',
        'report_date'
    ];

    public function instructors(){
        return $this->belongsTo(Instructor::class);
    }

    public function courses(){
        return $this->belongsTo(Course::class);
    }

}
