<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\MultipleUnique;

class TemplatesRequest extends FormRequest
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
        // rule template
         $rules =  array (
            'template.name' => 'required|max:50|unique:sc_templates,name',
            'template.description' => 'max:225',
            //
        );

        $template = $this->request->get('template');
        $template_id = $template['template_id'];
        if(!empty($template_id) || !is_null($template_id))
            $rules['template.name']         = ['required','max:50', Rule::unique('sc_templates','name')->ignore($template_id,'template_id')];

        // rule permission
        foreach ($this->request->get('permission') as $key => $item) {
            $rules['permission.' . $key . '.menu_flag'] = 'required|in:Y,N';

            if (!is_null($item['id']) || !empty($item['id'])) {
              // $rules['permission.' . $key . '.name']      = ['required', Rule::unique('sc_permissions', 'name')->ignore($item['id'])];
                $rules['permission.' . $key . '.name']      = ['required', new MultipleUnique('sc_permissions',['template_id'=>$template_id, 'name' => $item['name']],$item['id'])];
                $rules['permission.' . $key . '.slug']      = ['required', new MultipleUnique('sc_permissions',['template_id'=>$template_id, 'slug' => $item['slug']],$item['id'])];
                $rules['permission.' . $key . '.http_path'] = ['required', new MultipleUnique('sc_permissions',['template_id'=>$template_id, 'http_path' =>$item['http_path']],$item['id'])];
            } else {
                $rules['permission.' . $key . '.name']      = 'required|unique:sc_permissions,name';
                $rules['permission.' . $key . '.slug']      = 'required|unique:sc_permissions,slug';
                $rules['permission.' . $key . '.http_path'] = 'required|unique:sc_permissions,http_path';
            }
        }

        return $rules;
    }
}
