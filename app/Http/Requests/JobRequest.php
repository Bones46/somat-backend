<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\MultipleUnique;
use Illuminate\Validation\Rule;

class JobRequest extends FormRequest
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
        foreach($this->request->get('job') as $key => $item)
        {
            $rules['job.'.$key.'.position_id']        = array('required', Rule::exists('sc_position', 'position_id')                     
                                                                            ->where(function ($query) use ($school_id) {                      
                                                                                $query->where('school_id', $school_id);                  
                                                                            }));
            $rules['job.'.$key.'.job_name']                = array('required', new MultipleUnique('sc_jobs', ['job_name'=>$item['job_name'], 'school_id'=> $school_id]));
            $rules['job.'.$key.'.description']             = 'nullable';
            $rules['job.'.$key.'.grade']                   = 'nullable';
            $rules['job.'.$key.'.effective_start_date']    = 'required|date';
            $rules['job.'.$key.'.effective_end_date']      = 'nullable|date';
            
            if($item['job_id'])
                $rules['job.'.$key.'.name']= array('required', new MultipleUnique('sc_jobs', ['job_name'=>$item['job_name'], 'school_id'=> $school_id],$item['job_id'], 'job_id'));
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
