<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


class EmployeeRequest extends FormRequest
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
        $profileRule = (new ProfileRequest())->rules();
        $profile = $this->request->get('profile');
        $employee = $this->request->get('employee');
        
         $rules = [
            // employee rule
            'employee.nip'                      => 'required|max:20|unique:sc_employees,nip',
            'employee.nuptk'                    => 'nullable|max:20|unique:sc_employees,nuptk',
            'employee.degree'                   => 'nullable|max:30',
            'employee.status'                   => 'required',
            'employee.job_id'                   => 'nullable|exists:sc_jobs,job_id',
            'employee.effective_start_date'     => 'required|date',
            'employee.effective_end_date'       => 'nullable|date',
            'employee.jobs*'                    => 'required'
        ];
         
         
        if($profile['user_profile_id'])
        {
            $profileRule['profile.nik']     = array('required',  Rule::unique('sc_user_profile','nik')->ignore($profile['user_profile_id'],'user_profile_id'));
            $profileRule['profile.kk']      = array('nullable',  Rule::unique('sc_user_profile','kk')->ignore($profile['user_profile_id'],'user_profile_id'));
            $profileRule['profile.npwp']    = array('nullable',  Rule::unique('sc_user_profile','npwp')->ignore($profile['user_profile_id'],'user_profile_id'));
          
        }
        
        if($employee['employee_id'])
        {
            $rules['employee.nip']          = array('nullable',  Rule::unique('sc_employees','nip')->ignore($employee['employee_id'],'employee_id'));
            $rules['employee.nuptk']        = array('nullable',  Rule::unique('sc_employees','nuptk')->ignore($employee['employee_id'],'employee_id'));
        }
       
        
        $merge = array_merge($profileRule, (new LocationRequest())->rules());
        return array_merge($rules, $merge);
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
