<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Http\Requests\LocationsRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
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
        $profileRule['profile.nik']         = 'nullable|unique:sc_user_profile,nik';
        $profile = $this->request->get('profile');
        if($profile['user_profile_id'])
        {
            $profileRule['profile.nik']     = array('nullable',  Rule::unique('sc_user_profile','nik')->ignore($profile['user_profile_id'],'user_profile_id'));
            $profileRule['profile.kk']      = array('nullable',  Rule::unique('sc_user_profile','kk')->ignore($profile['user_profile_id'],'user_profile_id'));
            $profileRule['profile.npwp']    = array('nullable',  Rule::unique('sc_user_profile','npwp')->ignore($profile['user_profile_id'],'user_profile_id'));
        }
        
        $rules = [
            'student.person_id'             => 'nullable|exists:user_profile,user_profile_id',
            'student.certificate_number'    => 'nullable|max:50',
            'student.skhun'                 => 'nullable|max:50',
            'student.son_of'                => 'nullable',
            'student.class_level'           => 'nullable|max:10',
            'student.effective_start_date'  => 'required|date',
            'student.effective_end_date'    => 'nullable|date',
            'student.passed_flag'           => 'required|in:Y,N',
            'student.mutation_flag'         => 'required|in:Y,N'
        ];
        
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
