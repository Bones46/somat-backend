<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        $rules =  array (
            'role.name' => 'required|min:5|unique:sc_roles,name',
            'role.slug' => 'required|unique:sc_roles,slug|max:225',
            'group.*.permission_id' =>'required'
            //
        );

        $group = $this->request->get('role');
        $id = $group['id'];
        if(!empty($id) || !is_null($id))
        {
            $rules ['role.name'] = ['required', Rule::unique('sc_roles','name')->ignore($id,'id')];
            $rules ['role.slug'] = ['required', Rule::unique('sc_roles','slug')->ignore($id,'id')];
        }
        
        return $rules;
    }
}
