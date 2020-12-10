<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Permission;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        foreach($this->request->get('permission') as $key =>$item)
        {            
            $rules['permission.'.$key.'.menu_flag'] = 'required|in:Y,N';
            $rules['permission.'.$key.'.template_id'] = 'required';
            $id = $item['id'];
            
            if(!empty($id) || !is_null($id))
            {
                $rules['permission.'.$key.'.name']      =  ['required', 
                                                             Rule::unique('sc_permissions','name')->where (function ($query)use($item){
                                                                    return $query->where('template_id', $item['template_id']);
                                                            })->ignore($id)];
                $rules['permission.'.$key.'.slug']      =  ['required',  
                                                             Rule::unique('sc_permissions','slug')->where (function ($query)use($item){
                                                                    return $query->where('template_id', $item['template_id']);
                                                            })->ignore($id)];
                $rules['permission.'.$key.'.http_path'] =  ['required', Rule::unique('sc_permissions','http_path')->where (function ($query)use($item){
                                                                    return $query->where('template_id', $item['template_id']);
                                                            })->ignore($id)];
            }
            else
            {
                 $rules['permission.'.$key.'.name']      =  ['required', 
                                                             Rule::unique('sc_permissions','name')->where (function ($query)use($item){
                                                                    return $query->where('name', $item['name'])
                                                                                 ->where('template_id', $item['template_id']);
                                                            })];
                $rules['permission.'.$key.'.slug']      =  ['required',  
                                                             Rule::unique('sc_permissions', 'slug')->where (function ($query)use($item){
                                                                    return $query->where('template_id', $item['template_id']);
                                                            })];
                $rules['permission.'.$key.'.http_path'] =  ['required', Rule::unique('sc_permissions', 'http_path')->where (function ($query)use($item){
                                                                    return $query->where('template_id', $item['template_id']);
                                                            })];
            }
        }
        
        return $rules;
    }
}
