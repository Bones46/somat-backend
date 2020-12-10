<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\MultipleUnique;
use Illuminate\Validation\Rule;

class PositionRequest extends FormRequest
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
        foreach($this->request->get('position') as $key => $item)
        {
            $rules['position.'.$key.'.organization_id']      = array('required', Rule::exists('sc_organizations', 'organization_id')                     
                                                                                ->where(function ($query) use ($school_id) {                      
                                                                                    $query->where('school_id', $school_id);                  
                                                                                }));
            $rules['position.'.$key.'.name']                 = array('required', new MultipleUnique('sc_position', ['name'=>$item['name'], 'school_id'=> $this->query->get('school_id')]));
            $rules['position.'.$key.'.description']          = 'nullable';
            $rules['position.'.$key.'.effective_start_date'] = 'required|date';
            $rules['position.'.$key.'.effective_end_date']   = 'nullable|date';
            
            if($item['position_id'])
                $rules['position.'.$key.'.name']= array('required', new MultipleUnique('sc_position', ['name'=>$item['name'], 'school_id'=> $this->query->get('school_id')],$item['position_id'], 'position_id'));
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
