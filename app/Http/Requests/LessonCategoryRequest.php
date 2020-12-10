<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\MultipleUnique;

class LessonCategoryRequest extends FormRequest
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
        $school_id = $this->query->get('school_id');
        foreach ($this->request->get('category') as $key => $cat)
        {
            $rules['category.'.$key.'.name' ]           = $rules['category.'.$key.'.name']  = ['required', 'min:5',  new MultipleUnique('sc_lesson_category',['school_id'=>$school_id, 'name' => $cat['name']])];
            $rules['category.'.$key.'.description']     = 'nullable|max:240';
            $rules['category.'.$key.'.active_flag' ]    = 'required|in:Y,N';
            
            
            if(!is_null($cat['lesson_category_id']) || !empty($cat['lesson_category_id']))
                $rules['category.'.$key.'.name']  = ['required', 'min:5',  new MultipleUnique('sc_lesson_category',['school_id'=>$school_id, 'name' => $cat['name']], $cat['lesson_category_id'], 'lesson_category_id')];
        }
        
        return $rules;
    }
}
