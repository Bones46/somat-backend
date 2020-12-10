<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use App\Rules\UniqueStudentNisn;
use App\Rules\UniqueStudentNis;

class GlobalRequest extends FormRequest
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
        $data = $this->request->all();
        $arrKey = array_keys($data);
        $tmp1 = array();
        $tmp2 = array();
        $rules = array();

        foreach ($arrKey as $key) {
            $tmp1 = $this->getRules($key);
            if (!(is_null($this->getAddRules($key)))) {
                $id = Arr::get($data,$key.'.'.$key.'_id');
                if (is_null($id)) {
                    $tmp2 = $this->getAddRules($key);
                } else {
                    $tmp2 = $this->getEditRules($key, $id);
                }
                $tmp1 = array_merge($tmp1,$tmp2);
            }
            $rules = array_merge($rules,$tmp1);
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


    protected function getRules($keyname) {

        $arrRules = array (
            "school" => array (
                'school.0.name' => 'required|max:100',
                'school.*.image' => 'nullable|max:2000',
                'school.*.school_level_id' => 'nullable|exists:'.config('schoolconnect.database.school_level_table').',school_level_id',
                'school.*.type' => 'nullable|max:30',
                'school.*.acreditation' => 'nullable|max:3',
                'school.*.email' => 'nullable|max:30',
                'school.*.website' => 'nullable|max:100',
                'school.*.phone_number' => 'nullable|max:30',
                'school.*.head_master_person_id' => 'nullable|exists:'.config('schoolconnect.database.user_profile_table').',user_profile_id',
                'school.*.foundation_name' => 'nullable|max:150',
                'school.*.location_id' => 'nullable|exists:locations,location_id'


                'name' => 'required|max:100',
                'image' => 'nullable|max:2000',
                'school_level_id' => 'nullable|exists:school_level,school_level_id',
                'type' => 'nullable|max:30',
                'acreditation' => 'nullable|max:3',
                'email' => 'nullable|max:30',
                'website' => 'nullable|max:100',
                'phone_number' => 'nullable|max:30',
                'head_master_person_id' => 'nullable|exists:user_profile,user_profile_id',
                'foundation_name' => 'nullable|max:150',
                'location_id' => 'nullable|exists:locations,location_id'










				),
            "location" => array (
			
                'location.*.country' => 'required|max:50',
                'location.*.province' => 'required|max:50',
                'location.*.district' => 'required|max:30',
                'location.*.sub_districts' => 'nullable|max:30',
                'location.*.village' => 'nullable|max:30',
                'location.*.neighbourhood' => 'nullable|max:30',
                'location.*.hamlet' => 'nullable|max:30',
                'location.*.postal_code' => 'required|max:10',
                'location.*.address' => 'nullable|max:240'
				
                'country' => 'required|max:50',
                'province' => 'required|max:50',
                'district' => 'required|max:30',
                'sub_districts' => 'nullable|max:30',
                'village' => 'nullable|max:30',
                'neighbourhood' => 'nullable|max:30',
                'hamlet' => 'nullable|max:30',
                'postal_code' => 'required|max:10',
                'address' => 'nullable|max:240'
				
				
            ),
            "student" => array (
                'student.person_id' => 'required|exists:user_profile,user_profile_id',
                'student.certificate_number' => 'nullable|max:50',
                'student.skhun' => 'nullable|max:50',
                'student.son_of' => 'nullable',
                'student.class_level' => 'nullable|max:10',
                'student.effective_start_date' => 'nullable|date',
                'student.effective_end_date' => 'nullable|date',
                'student.passed_flag' => 'nullable|date',
                'student.mutation_flag' => 'nullable|date'
            ),
            "profile" => array (
                'profile.full_name' => 'required|max:240',
                'profile.mobile_number' => 'nullable|max:20',
                'profile.phone_no' => 'nullable|max:20',
                'profile.place_of_birth' => 'nullable|max:50',
                'profile.date_of_birth' => 'nullable|date',
                'profile.gender' => 'nullable|max:1',
                'profile.religion' => 'nullable|max:50',
                'profile.job' => 'nullable|max:50',
                'profile.blood_type' => 'nullable|max:2',
                'profile.salary' => 'nullable|max:15',
                'profile.graduated' => 'nullable|max:10',
                'profile.profile_image' => 'nullable|max:2000',
                'profile.effective_start_date' => 'nullable|date',
                'profile.effective_end_date' => 'nullable|date',
                'profile.location_id' => 'nullable|exists:locations,location_id',
                'profile.status' => 'nullable|max:15',
                'profile.citizenship' => 'nullable|max:5',
                'profile.country' => 'nullable|max:20'
            )
        );


        $arrReturn = Arr::get($arrRules, $keyname);

        return $arrReturn;

    }

    public function attributes(){
      return [
          'school.*.name' => 'Nama Sekolah',
      ];
    }

    protected function getAddRules($keyname) {
        $arrRules = array (
            "school" => array (
                'school.*.registration_number' => 'nullable|unique:school|max:50'
            ),
            "student" => array (
                'student.*.school_id' => 'required|unique:students',
                'student.*.nisn' => ['required',new UniqueStudentNisn],
                'student.*.nis' => ['required',new UniqueStudentNis]
            )
        );

        $arrReturn = Arr::get($arrRules, $keyname);

        return $arrReturn;
    }

    protected function getEditRules($keyname, $id) {
        $arrRules = array (
            "school" => array (
                'school.*.registration_number' => ['nullable',Rule::unique('school')->ignore($id,'school_id'),'max:50']
            ),
            "student" => array (
                'student.*.school_id' => ['required',Rule::unique('students')->ignore($id,'student_id')],
                'student.*.nisn' => ['required',Rule::unique('students')->ignore($id,'student_id'),'max:30'],
                'student.*.nis' => ['required',Rule::unique('students')->ignore($id,'student_id'),'max:30']
            )
        );

        $arrReturn = Arr::get($arrRules, $keyname);

        return $arrReturn;
    }
}
