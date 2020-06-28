<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
 use App\Models\MainCategory;
use Illuminate\Http\Request;

class VendorsController extends Controller
{
    //
    public function index(){
    $vendors =  Vendor::selection()->paginate(PAGINATION_COUNT);
    return view('admin.vendors.index', compact('vendors'));
    }


    public function create(){
        //each vendor belongs to mainCat so, get the mainCat first//
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(Request $request){
//return $request;

        try{




        }catch ( \Exception $ex){
           // return $ex;


        }
    }

    public function edit(){

    }

    public function update(){

    }

    public function delete(){

    }
}
