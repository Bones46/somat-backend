<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LookupRequest extends FormRequest
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
        $lookup= $this->request->get('lookup');
        $rules = [ 
            'lookup.description' => 'nullable|max:240',
            'lookup.effective_start_date' => 'nullable|date',
            'lookup.effective_end_date' => 'nullable|date',
            'lookup.lookup_name' => 'required|unique:sc_lookup,lookup_name|max:50'
        ];
        
        if ($lookup['lookup_id']) 
            $rules['lookup.lookup_name'] = ['required',Rule::unique('sc_lookup')->ignore($id,'lookup_id'),'max:50'];
        
        foreach($this->request->get('lookupcode') as $key => $item)
        {
            $rules['lookupcode.'.$key.'.description']            = 'nullable|max:240';
            $rules['lookupcode.'.$key.'.effective_start_date']   = 'required|date';
            $rules['lookupcode.'.$key.'.effective_end_date']     = 'nullable|date';
            $rules['lookupcode.'.$key.'.sequence']               = 'nullable|max:240';
            $rules['lookupcode.'.$key.'.lookup_code_name']       = array('required',Rule::unique('sc_lookup_code', 'lookup_code_name')                     
                                                                                    ->where(function ($query) use ($lookup) {                      
                                                                                        $query->where('lookup_name', $lookup['lookup_name']);                  
                                                                                    }),'max:50');         

            if ($item['lookup_code_id']) {
              $rules['lookupcode.'.$key.'.lookup_code_name']       = array('required',Rule::unique('sc_lookup_code', 'lookup_code_name')                     
                                                                                        ->where(function ($query) use ($lookup) {                      
                                                                                            $query->where('lookup_name', $lookup['lookup_name']);                  
                                                                                        })->ignore('lookup_code_id', $item['lookup_code_id']),'max:50');

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
