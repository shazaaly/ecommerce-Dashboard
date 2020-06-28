<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Requests\LanguageRequest;
use mysql_xdevapi\Exception;


class LanguagesController extends Controller
{
    //
    public function index(){
       $languages =  Language::selection()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', compact('languages'));

    }

    public function create(){
    return view('admin.languages.create');
    }

    public function store(LanguageRequest $request){

try{
    Language::create($request->except(['_token']));
    return redirect()->route('admin.languages')->with(['success'=> 'تم حفظ اللغه بنجاح']);
}catch (\Exception $exception){
    return redirect()->route('admin.languages')->with(['error'=> 'خطأ ما في حفظ اللغه, يرجى المحاوله في وقت لاحق ']);


};

    }

    public function edit($id){
            $language =  Language::selection()->find($id);
            if (!$language){
                return redirect()->route('admin.languages')->with(['error'=>'هذه لغة غير موجودة']);
            }
            return view('admin.languages.edit', compact('language'));
    }

    public function update(LanguageRequest $request,  $id ){
       try{
           $language =  Language::find($id);
           if (!$language){
               return redirect()->route('admin.languages.edit', $id)->with(['error'=>'هذه لغة غير موجودة']);
           }
           //update
           if (!$request->has('active'))
           $request->request->add(['active' => 0]); //add request

           $language-> update($request->except('_token'));
           return redirect()->route('admin.languages')->with(['success'=>'تم تحديث اللغه بنجاح']);

       }catch (Exception $ex){
           return redirect()->route('admin.languages')->with(['error'=>'برجاء المحاوله في وقت لاحق']);

       }
    }

    public function destroy($id){
      try{ $language = Language::find($id);
        if (!$language){
            return redirect()->route('admin.languages')->with(['error'=>'هذه لغة غير موجودة']);
        }
        $language->delete();
        return redirect()->route('admin.languages')->with(['success'=>'تم حذف اللغه بنجاح']);
      }catch (\Exception $ex){
          return redirect()->route('admin.languages')->with(['error'=>'برجاء المحاوله لاحقا']);


      }



    }
}
