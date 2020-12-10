<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return FALSE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         $rules = [
            'name' => 'required|unique:permissions',
            'slug' => 'required|unique:permissions',
            'http_path' => 'required|unique:permissions',
            'menu_flag' => 'required|in:Y,N',
        ];
    }
    
    
   
}
