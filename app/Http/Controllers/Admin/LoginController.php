<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AdminLoginRequest;

class LoginController extends Controller
{
    //
public function getLogin(){
return view('admin.loginDashboard');
}


    public function login(AdminLoginRequest $request){
    $remember_me= $request->has('remember_me')?true:false;

   if (auth()->guard('admin')->attempt([
       'email'=>$request->input('email'),
       'password'=>$request->input('password')])
   ){
      return redirect('admin.dashboard');
   }return redirect()->back()->with(['error'=>'خطأ في معلومات الدخول']);

    }
}
