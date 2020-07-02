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


}
