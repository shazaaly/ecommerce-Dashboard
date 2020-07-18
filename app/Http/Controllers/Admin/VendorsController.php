<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use App\Http\Requests\VendorsRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorCreated;
use Illuminate\Support\Str;
use function Sodium\add;
class VendorsController extends Controller
{
    //
    public function index()
    {
        $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorsRequest $request)
    {
        try {

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }

            $vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'address' => $request->address,
                'logo' => $filePath,
                'category_id'  => $request -> category_id,
                'password'=>$request->password
            ]);

            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
//return $ex;
           return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }

    public function edit($id)
    {
        try {

            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);

            $categories = MainCategory::where('translation_of', 0)->active()->get();

            return view('admin.vendors.edit', compact('vendor', 'categories'));

        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public  function update(Request $request, $id){
       // return $request;

        try{
            $vendor = Vendor::Selection()->find($id);
            if (!$vendor)
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);

            DB::beginTransaction();
//update photo :  //if request has the photo it will bw updated.otherwise kept as it is:
            if ($request->has('logo')){
                $file_path= uploadImage('vendors', $request->logo);
                Vendor::where('id', $id)->update([

                    'logo'=>$file_path
                ]);
            }

           $data= $request->except('_token', 'id', 'password', 'logo');

            //update password :  //if request has other pass it will bw updated.otherwise kept as it is:

            if ($request->has('password')){
                   $date['password']= $request->password;
            }
            //update
            Vendor::where('id', $id)->update($data);
            DB::commit();

            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);


        }catch (\Exception $ex){
            DB::rollBack();
            return $ex;
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }

    }
    public function destroy($id){
        try{
            $vendor=   Vendor::find($id);

            if (!$vendor){
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المورد غير موجود ']);
            }

            // return $mainCategory->photo;
            // unlink($mainCategory->photo); unlink can not be used with asset => http://localhost part//
            //unlink deal with path like C:\xampp\htdocs\ecommerce\app\: so 1- cut http part
            //to cut http part before assets//---
            //now problem is slash \/:  C:\xampp\htdocs\ecommerce\app\/images/mainCategories/rRvjv0lVquD9Mn7AZ91khhoIjrB9FE1fNbH2uk77.jpeg

            $image =  Str::after($vendor->logo, 'assets/');
            //2- get internal path:
            // return base_path($image);
            //now we can use unlink ()//
            $image =  base_path('assets/'.$image);
            unlink($image);
            //             delete translation langs to same mainCat from Database--check relations in related Model//
            $vendor->delete();
            return redirect()->route('admin.vendors')->with(['success' => 'تم الحذف بنجاح']);



        }catch (\Exception $exception){
            return $exception;
            return redirect()->route('admin.vendors')->with(['error' => 'خطأ في حذف البيانات! ']);
        }

    }

    public function changeStatus($id){
        try{
            $vendor=   Vendor::find($id);
            if (!$vendor){
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المورد غير موجود ']);
            }

            $status =  $vendor->active ==0 ? 1:0;
            $vendor->update([ 'active' => $status  ]);
            return redirect()->route('admin.vendors')->with(['success'=>'تم تعديل الحالة بنجاح']);

        }catch (\Exception $exception){
            return redirect()->route('admin.vendors')->with(['error' => 'خطأ في تعديل البيانات! ']);


        }



    }




}
