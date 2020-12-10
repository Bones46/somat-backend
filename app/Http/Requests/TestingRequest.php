<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [ 
            'permission.name.*' => 'required|unique:permissions',
            'slug' => 'required|unique:permissions',
            'http_path' => 'required|unique:permissions',
            'menu_flag' => 'required|in:Y,N',
        ];
    }
    
     /*
    * Overwrite Laravel protected method "failedValidation"  
    * for throw json response
    * required :
    * Illuminate\Contracts\Validation\Validator;
    * Illuminate\Http\Exceptions\HttpResponseException;
    */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
