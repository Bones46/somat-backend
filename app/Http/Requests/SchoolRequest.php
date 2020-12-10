<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SchoolRequest extends FormRequest
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
            'school.registration_number'    => 'required|unique:sc_school,registration_number',
            'school.name'                   => 'required|max:100',
            'school.status'                 => 'required|max:1|in:S,N',
            'school.image'                  => 'nullable|max:2000',
            'school.school_level_id'        => 'nullable|exists:sc_school_level,school_level_id',
            'school.study_time'             => 'nullable|max:1|in:P,S,B',
            'school.acreditation'           => 'nullable|max:3',
            'school.email'                  => 'nullable|email|max:30',
            'school.website'                => 'nullable|max:100',
            'school.phone_number'           => 'nullable|max:30',
            'school.foundation_name'        => 'nullable|max:150',
            'school.location_id'            => 'nullable|exists:sc_locations,location_id'
        ];
        
        $school = $this->request->get('school');
        if(!empty($school['school_id']) || !is_null($school['school_id']))
             $rules['school.registration_number'] = ['required', Rule::unique('sc_school','registration_number')->ignore($school['school_id'],'school_id')];
        
        
        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'school.registration_number'    => 'NPSN',
            'school.name'                   => 'Nama Sekolah',
            'school.image'                  => 'Lambang Sekolah',
            'school.school_level_id'        => 'Jenjang Pendidikan',
            'school.type'                   => 'Type Sekolah',
            'school.acreditation'           => 'Akreditasi Sekolah',
            'school.email'                  => 'Email',
            'school.website'                => 'Website',
            'school.phone_number'           => 'No. Telp',
            'school.head_master_person_id'  => 'Kepala Sekolah',
            'school.foundation_name'        => 'Nama Yayasan',
            'school.location_id'            => 'Lokasi Sekolah'
        ];
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
