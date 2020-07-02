<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //any unique shd has .$this->>id to escape unique validation upon escape//
        return [
            'logo' => 'required_without:id|mimes:jpg,jpeg,png',
            'name' => 'required|string|max:100',
            'mobile' =>'required|max:100|unique:vendors,mobile,'.$this->id,
            'email'  => 'required|email|unique:vendors,email,'.$this->id,
            'category_id'  => 'required|exists:main_categories,id',
            'address'   => 'required|string|max:500',
            'password'=>'required_without:id|string|min:6'

        ];
    }


    public function messages(){

        return [
            'required'  => 'هذا الحقل مطلوب ',
            'max'  => 'هذا الحقل طويل',
            'category_id.exists'  => 'القسم غير موجود ',
            'email.email' => 'ضيغه البريد الالكتروني غير صحيحه',
            'email.unique' => ' البريد الالكتروني مستخدم ',
            'mobile.unique' => ' الهاتف مسجل من قبل ',
            'address.string' => 'العنوان لابد ان يكون حروف او حروف وارقام ',
            'name.string'  =>'الاسم لابد ان يكون حروف او حروف وارقام ',
            'logo.required_without'  => 'الصوره مطلوبة',
            'password'=>'برجاء ادخال كلمة مرور لا تقل عن ستة أحرف'


        ];
    }
}
