<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    //
    protected $table = 'main_categories';
    //
    protected $fillable= [
        'name', 'slug', 'photo', 'translation_lang', 'translation_of', 'active', 'translation_of', 'created_at', 'updated_at'
    ];

    public function scopeActive($query){
        return $query->where('active', 1);
    }
    public function scopeSelection($query){
        return $query->select('id', 'name', 'photo','translation_lang', 'slug', 'active');
    }

    public function getActive(){

        return $this->active == 1 ? 'مفعل':'غير مفعل';
    }


    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('assets/' . $val) : "";

    }

    public  function categories(){  //add foreifn key to select statement==method here//
        return $this->hasMany(self::class, 'translation_of');
    }

    //Relation: Each vendor belongs to Only One Main Category===>Whils Main Cat HAS Many Vendors//
    public function vendors(){
        return $this->hasMany('App\Models\Vendor', 'category_id');
    }


}

