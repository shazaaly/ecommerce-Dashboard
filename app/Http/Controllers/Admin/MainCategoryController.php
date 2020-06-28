<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Models\MainCategory;
use App\Http\Requests\MainCategoryRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class MainCategoryController extends Controller
{
    //
    public function index(){

        $default_lang =  get_default_function();
        $mainCategories = MainCategory::where('translation_lang' , $default_lang)->selection()->paginate(PAGINATION_COUNT);
         return view('admin.mainCategories.index', compact('mainCategories'));
    }

    public function create(){
        return view('admin.mainCategories.create');
    }

    public function store(MainCategoryRequest $request){
      //  return $request;
//Aim:   Non def lang are: 'translation_of'=>  $default_category_id , so we need to separate them
//to filter returned request to the chosen lang only then inserting it in database:
        //=================================================================================//
try {
    $main_category = collect($request->category);
    $filter = $main_category->filter(function ($value, $key) {
        return $value['abbr'] == get_default_function();

    });
    //  return $filter;  = filtered request

    //photo file path:
    $file_path = "";
    if ($request->has('photo')) {
        $file_path = uploadImage('mainCategories', $request->photo);

    }
    $default_category = array_values($filter->all()) [0];     // array of object , no keys as $filter???

    //If the table has an auto-incrementing id, use the insertGetId method to insert a record and then retrieve the ID://

    DB::beginTransaction();

    $default_category_id = MainCategory::insertGetId([
        'translation_lang' => $default_category['abbr'],
        'translation_of' => 0,
        'name' => $default_category['name'],
        'slug' => $default_category['name'],
        'photo' => $file_path
    ]);                                         //returned 1 //


    // Languages other than default:   !=  //
    $categories = $main_category->filter(function ($value, $key) {
        return $value['abbr'] != get_default_function();

    });

    //storing non default langs in DB:  //

    $categories_arr = [];

    if (isset($categories) && $categories->count()) {
        foreach ($categories as $category) {
            $categories_arr[] = [
                'translation_lang' => $category['abbr'],
                'translation_of' => $default_category_id,
                'name' => $category['name'],
                'slug' => $category['name'],
                'photo' => $file_path
            ];
        }

        MainCategory::insert($categories_arr);
    }

    DB::commit();
    return redirect()->route('admin.mainCategories')->with(['success'=>'تم حفظ القسم بنجاح']);


}catch (\Exception $ex){
    DB::rollBack();
    return redirect()->route('admin.mainCategories.create')->with(['error'=>'خطأ في تسجيل البيانات']);

}

    }

    public function edit($mainCat_id){
    //get slected category with its all translations//  MainCategory::with('categories')->selection()//
        //check id is in main categories:
        $mainCategory =  MainCategory::with('categories')->selection()->find($mainCat_id);

          if (!$mainCategory){
              return redirect()->route('admin.mainCategories')->with(['error'=> 'لا يوجد هذا القسم في قاعدة البيانات']);
          }

          return view('admin.mainCategories.edit', compact('mainCategory'));

    }

    public function update($mainCat_id, MainCategoryRequest $request)
    {

        try {
            $main_category = MainCategory::find($mainCat_id);

            if (!$main_category)
                return redirect()->route('admin.mainCategories')->with(['error' => 'هذا القسم غير موجود ']);
            // update date
            $category = array_values($request->category) [0];

            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            MainCategory::where('id', $mainCat_id)
                ->update([
                    'name' => $category['name'],
                    'active' => $request->active,
                ]);

            // save image

            if ($request->has('photo')) {
                $filePath = uploadImage('mainCategories', $request->photo);
                MainCategory::where('id', $mainCat_id)
                    ->update([
                        'photo' => $request->$filePath,
                    ]);
            }

            return redirect()->route('admin.mainCategories')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (\Exception $ex) {
            return $ex;
          //  return redirect()->route('admin.mainCategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }
}
