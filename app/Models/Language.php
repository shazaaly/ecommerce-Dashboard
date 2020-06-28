<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    //
    protected $table = 'languages';
    //
    protected $fillable= [
        'name', 'abbr', 'locale', 'direction', 'active', 'created_at', 'updated_at'
    ];

    public function scopeActive($query){
        return $query->where('active', 1);
    }

    public  function scopeSelection($query){
        return $query->select('name', 'abbr', 'direction', 'active','id');
    }

    public  function getActive(){
         return $this->active == 1 ? 'مفعل': 'غير مفعل';

    }


}
