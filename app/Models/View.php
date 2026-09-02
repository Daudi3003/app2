<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    protected $fillable = [
        'viewed_at'
    ];

     public function material(){
        return $this->belongsTo(Material::class);
    }

    public function students(){
        return $this->belongsTo(Student::class);
    }
}
