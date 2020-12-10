<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MultipleUnique;


class ActivityRequest extends FormRequest
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
        $school_id =$this->query->get('school');
        
        foreach($this->request->get('activity') as $key => $item)
        {
            $rules['activity.'.$key.'.activity_name']   =  array('required', new MultipleUnique('sc_activity',['school_id'=>$school_id, 'activity_name' => $item['activity_name']]));
            $rules['activity.'.$key.'.description']     = 'required';
            $rules['activity.'.$key.'.active_flag']     = 'in:Y,N';
            $rules['activity.'.$key.'.category_code']   = 'in:E,P';
            
            if(!is_null($item['activity_id']))
                $rules ['activity.'.$key.'.activity_name']  = array('required', new MultipleUnique('sc_activity',['school_id'=>$school_id, 'activity_name' => $item['activity_name']],$item['activity_id'],'activity_id' ));
        }
        
        return $rules;
    }
}
