<?php

namespace App\Http\Controllers;
use App\Models\MainCategory;

use Illuminate\Http\Request;

class CollController extends Controller
{
    //
    public function complex(){
      $categories =  MainCategory::get();   //All data as an array of objects//

     // remove

        $categories->each(function ($category){
            if ($category->id ==31) {
                unset($category->translation_lang);
                unset($category->translation_of);
                $category->name = "ahmed";
            }
            return $category;
        });
        return $categories;

        //add
    }

    public  function  complexFilter(){
        $categories =  MainCategory::get();   //array of objects//
        $categories = collect($categories);
        $filterCat = $categories->filter(function ($value, $key){
            return $value['translation_lang'] =="ar";
        });

       // return $filterCat;   //  return  wz key when use function array_values()//  so convert to array by all() :
        return array_values($filterCat-> all());

    }

    public  function  complexTransform(){
        $categories =  MainCategory::get();   //array of objects//
        $categories = collect($categories);
       return $filterTrans = $categories->transform(function ($value, $key){
           $data = [];
             $data['name']= $value['name'];
              $data['age']= 30;
              return $data;

        });


    }
}
