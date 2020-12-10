<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\MultipleUnique;

class OrganizationRequest extends FormRequest
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
        foreach($this->request->get('organization') as $key => $item)
        {
            $rules['organization.'.$key.'.name']                 = array('required', new MultipleUnique('sc_organizations', ['name'=>$item['name'], 'school_id'=> $this->query->get('school_id')]));
            $rules['organization.'.$key.'.description']          = 'nullable';
            $rules['organization.'.$key.'.effective_start_date'] = 'required|date';
            $rules['organization.'.$key.'.effective_end_date']   = 'nullable|date';
            
            if($item['organization_id'])
                $rules['organization.'.$key.'.name']= array('required', new MultipleUnique('sc_organizations', ['name'=>$item['name'], 'school_id'=> $this->query->get('school_id')],$item['organization_id'], 'organization_id'));
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
