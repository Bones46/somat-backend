<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonTimeRequest extends FormRequest
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
        $rules = array(  
                        'lessontime.*.start_time'   => 'required',
                        'lessontime.*.end_time'     => 'required',
                        'lessontime.*.study_time'   => 'in:P,S',
                        'lessontime.*.active_flag'  => 'in:Y,N',
                        'lessontime.*.category_code'  => 'in:E,P',
                    );
        
        return $rules;
    }
}
