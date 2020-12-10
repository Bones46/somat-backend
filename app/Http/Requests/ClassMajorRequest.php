<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\MultipleUnique;

class ClassMajorRequest extends FormRequest
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
        $school_id = $this->query->get('school');
        foreach($this->request->get('classmajor') as $key => $item)
        {
                $rules['classmajor.'.$key.'.name' ]             = ['required', 'max:50', new MultipleUnique('sc_class_major',['school_id'=>$school_id, 'name' => $item['name']])];
                $rules['classmajor.'.$key.'.description']       = 'nullable|max:240';
                $rules['classmajor.'.$key.'.active_flag']       = 'required|in:Y,N';
                $rules['classmajor.'.$key.'.major_code']        = array('required', new MultipleUnique('sc_class_major',['school_id'=>$school_id, 'name' => $item['major_code']]));
                
            if($item['class_major_id']){
                $rules['classmajor.'.$key.'.name' ]             =  array('required', new MultipleUnique('sc_class_major',['school_id'=>$school_id, 'name' => $item['name']], $item['class_major_id'], 'class_major_id'));
                $rules['classmajor.'.$key.'.major_code']        =  array('required', new MultipleUnique('sc_class_major',['school_id'=>$school_id, 'name' => $item['major_code']], $item['class_major_id'], 'class_major_id'));
            }
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
  