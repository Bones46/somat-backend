<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Rules\MultipleUnique;

class AcademicYearRequest extends FormRequest
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
        $schoolYear = $this->request->get('academicyear');
        $school_id = $this->query->get('school_id');
        $rules = [
            //'academicyear.school_id'          => 'required|exists:sc_school,school_id',
            'academicyear.name'               => 'max:50',
            'academicyear.curriculum_name'    => 'nullable|max:50',
            'academicyear.start_year'         => array('required', new MultipleUnique('sc_academic_year',array('school_id'=>$school_id, 'start_year' => $schoolYear['start_year']))),
            'academicyear.end_year'           => array('required', new MultipleUnique('sc_academic_year',array('school_id'=>$school_id, 'end_year' => $schoolYear['end_year']))),
        ];
            
        if(!is_null($schoolYear['academic_year_id'])|| !empty($schoolYear['academic_year_id'])){
            $rules['academicyear.start_year'] = [ 'required', new MultipleUnique('sc_academic_year',array('school_id'=>$school_id, 
                                                                                                      'start_year' => $schoolYear['start_year']), 
                                                                                                $schoolYear['academic_year_id'],
                                                                                                'academic_year_id')];
            
           $rules['academicyear.end_year'] = [ 'required', new MultipleUnique('sc_academic_year',array('school_id'=>$school_id, 
                                                                                                      'start_year' => $schoolYear['end_year']), 
                                                                                                $schoolYear['academic_year_id'],
                                                                                                'academic_year_id')];
        }
        
        return array_merge($rules, (new AcademicPeriodRequest())->rules());
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
