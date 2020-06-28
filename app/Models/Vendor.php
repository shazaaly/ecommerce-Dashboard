<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Vendor extends Model
{
    //
    protected $table = 'vendors';
    protected $fillable = [
        'category_id', 'active', 'name', 'logo', 'mobile','address', 'email', 'created_at',	'updated_at'
    ];

    protected $hidden =[
        'category_id'
    ];

    public  function scopeSelection($query){
        return $query->select( 'category_id', 'name','active', 'logo', 'mobile');
    }

    public function scopeActive($query){

        return $query->where('active', 1);
    }

    public function getActive(){

        return $this->active == 1? 'مفعل':'غير مفعل';
    }
    //fn to save logo(photo) in folder:vendors defind in file systems:
    public function getLogoAttribute($val){

    return $val != null? asset('assets/'.$val): null;

    }

    //Relation: Each vendor belongs to Only One Main Category===>Whils Main Cat HAS Many Vendors//

    public function category(){
        return $this->belongsTo('App\Models\MainCategory', 'category_id');
    }
}
