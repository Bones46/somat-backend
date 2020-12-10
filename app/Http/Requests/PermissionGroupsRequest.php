<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionGroupsRequest extends FormRequest
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
        $rules =  array (
            'group.name' => 'required|min:5|unique:sc_permission_groups,name',
            'group.description' => 'max:225',
            'permissions.*.id' =>'required'
            //
        );
        
        $group = $this->request->get('group');
        if(array_key_exists('permission_group_id',$group ))
        {
            unset($rules['group.name']);
            $rules ['group.name'] = ['required', Rule::unique('sc_permission_groups','name')->ignore($group['permission_group_id'],'permission_group_id')];
        }
        
        return $rules;
    }
}
