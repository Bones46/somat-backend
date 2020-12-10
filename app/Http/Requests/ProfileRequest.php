<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class ProfileRequest extends FormRequest
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
        $rules = [
            'profile.nik'               => 'required|unique:sc_user_profile,nik',
            'profile.kk'                => 'nullable|unique:sc_user_profile,kk',
            'profile.npwpw'             => 'nullable|unique:sc_user_profile,npwp',
            'profile.first_name'        => 'required|max:240',
            'profile.last_name'         => 'nullable|max:240',
            'profile.mobile_number'     => 'required|max:20',
            'profile.phone_no'          => 'nullable|max:20',
            'profile.place_of_birth'    => 'required|max:50',
            'profile.date_of_birth'     => 'required|date',
            'profile.gender'            => 'required|max:1',
            'profile.religion'          => 'required|max:50',
            'profile.job'               => 'nullable|max:50',
            'profile.blood_type'        => 'nullable|max:2',
            'profile.salary'            => 'nullable|max:15',
            'profile.graduated'         => 'nullable|max:30',
            'profile.profile_image'     => 'nullable|max:240',
            'profile.email'             => 'required|max:240',
            'profile.title_first'       => 'nullable|max:240',
            'profile.title_last'        => 'nullable|max:240',
            'profile.status'            => 'nullable|max:15',
            'profile.citizenship'       => 'nullable|max:5',
            'profile.country'           => 'nullable|max:20',
        ];
        
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
