<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\MultipleUnique;
use Illuminate\Validation\Rule;

class ClassRequest extends FormRequest
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
        $school_id = $this->query->get('school_id');
        foreach($this->request->get('class') as $key => $item)
        {
            $rules['class.'.$key.'.class_name']           = array('required', new MultipleUnique('sc_class',['school_id'=>$school_id, 'class_name' => $item['class_name']]));
            $rules['class.'.$key.'.class_level']          = 'required|max:10';
            $rules['class.'.$key.'.class_major_id']       = array('nullable', Rule::exists('sc_class_major', 'class_major_id')                     
                                                                                ->where(function ($query) use ($school_id) {                      
                                                                                    $query->where('school_id', $school_id);                  
                                                                                }));
            $rules['class.'.$key.'.category_class_id']    = array('nullable', Rule::exists('sc_lesson_category', 'lesson_category_id')                     
                                                                                ->where(function ($query) use ($school_id) {                      
                                                                                    $query->where('school_id', $school_id);                  
                                                                                }));
            $rules['class.'.$key.'.class_code']           = 'nullable|max:15';
            $rules['class.'.$key.'.active_flag']          = 'required|in:Y,N';
            
            if($item['class_id'])
                $rules['class.'.$key.'.class_name']     = array('required', new MultipleUnique('sc_class',['school_id'=>$school_id, 'class_name' => $item['class_name']], $item['class_id'],'class_id'));
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
