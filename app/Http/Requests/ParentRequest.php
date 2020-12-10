<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

use App\Rules\UniqueStudentNisn;
use App\Rules\UniqueStudentNis;

class ParentRequest extends FormRequest
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
            // location rule
            'parent.*.location.hamlet' => 'nullable|max:30',
            'parent.*.location.village_id' => 'required|max:10',
            'parent.*.location.address' => 'nullable|max:240',
            'parent.*.location.neighbourhood' => 'nullable|max:240',
            //profile rule
            'parent.*.profile.full_name' => 'required|max:240',
            'parent.*.profile.mobile_number' => 'nullable|max:20',
            'parent.*.profile.phone_no' => 'nullable|max:20',
            'parent.*.profile.place_of_birth' => 'nullable|max:50',
            'parent.*.profile.date_of_birth' => 'nullable|date',
            'parent.*.profile.gender' => 'nullable|max:1',
            'parent.*.profile.religion' => 'nullable|max:50',
            'parent.*.profile.job' => 'nullable|max:50',
            'parent.*.profile.blood_type' => 'nullable|max:2',
            'parent.*.profile.salary' => 'nullable|max:15',
            'parent.*.profile.graduated' => 'nullable|max:10',
            'parent.*.profile.profile_image' => 'nullable|max:240',
            'parent.*.profile.email' => 'nullable|max:240',
            //'student.profile.effective_start_date' => 'nullable|date',
            //'student.profile.effective_end_date' => 'nullable|date',
            'parent.*.profile.location_id' => 'nullable|exists:locations,location_id',
            'parent.*.profile.status' => 'nullable|max:15',
            'parent.*.profile.citizenship' => 'nullable|max:5',
            'parent.*.profile.country' => 'nullable|max:20',
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
