<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Libraries\Lesson;

class LessonRequest extends FormRequest
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
                        'lesson.lesson_category_id' => 'required|exists:sc_lesson_category,lesson_category_id',
                        'lesson.name'               => 'required|unique:sc_lesson,name|min:3',
                        'lesson.group_flag'         => 'required|in:Y,N',
                        'lesson.active_flag'        => 'required|in:Y,N'
                    );
        
        
        if($this->request->get('key')== Lesson::EXTCUR_LESSON)
            unset($rules['lesson.lesson_category_id']);
                
        $lesson = $this->request->get('lesson');
        if(array_key_exists('lesson_id', $lesson))
              $rules ['lesson.name']                = ['required', Rule::unique('sc_lesson','name')->ignore($lesson['lesson_id'],'lesson_id')];
        
        if($this->request->get('group') && $lesson['group_flag'] === 'Y')
        {
            $group =$this->request->get('group');
            foreach($group as $key => $item)
            {
                $lesson_id = $item['lesson_id'];
                $rules['group.'.$key.'.name']        = ['required','unique:sc_lesson,name','min:3'];
                $rules['group.'.$key.'.active_flag'] = ['required','in:Y,N',];
                
                if(!empty($lesson_id) || is_null($lesson_id))
                    $rules ['group.'.$key.'.name']   = ['required', Rule::unique('sc_lesson','name')->ignore($lesson_id,'lesson_id')];
            }
        }
        return $rules;
    }
}
