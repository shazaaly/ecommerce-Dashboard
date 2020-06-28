<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
        return [
            //
            'abbr'=>'required|string|max:10',
        'name'=>'required|string|max:100',
        'direction'	=>'required|in:rtl,ltr'

        ];
    }

    public function messages()
    {
        return [
        'required'       =>'هذا الحقل مطلوب',
        'name.string'=>'أسم اللغه لابد أن يكون أحرف فقط',
        'name.max'=>'أسم اللغه لابد أن لا يزيد عن 10 أحرف فقط',
        'direction.in'	=>'اتجاه اللغه اما يمين لليسار أو من اليسار لليمين فقط'
        ];
    }
}
