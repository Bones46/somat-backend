<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SchoolLevelRequest extends FormRequest
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
        foreach($level = $this->request->get('schoollevel') as $key =>$item)
        {
            $rules  = array('schoollevel.'.$key.'.description'  =>  'nullable|max:240',
                            'schoollevel.'.$key.'.active_flag'  =>  'nullable|max:1',
                            'schoollevel.'.$key.'.name'         =>  'required|max:50|unique:sc_school_level,name'
                );
            
             if (!is_null($item['school_level_id'])|| !empty($item->school_level_id)) 
                $rules ['schoollevel.'.$key.'.name'  ]= ['required',Rule::unique('school_level')->ignore($item->school_level_id,'school_level_id'),'max:50']; 
        }
        
        return $rules;
    }

    /*
    * Overwrite Laravel protected method "failedValidation"  
    * untuk throw json response
    * required :
    * Illuminate\Contracts\Validation\Validator;
    * Illuminate\Http\Exceptions\HttpResponseException;
    */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
