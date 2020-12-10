<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\Permission as mPermission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesRepository extends Repository //implements libraryContract
{ 
    protected $permission;

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {            
        parent::__construct('Role',$id,$data);
    }
    
    /**
     * retrieve permission group 
     * if administrator get all permission group, besides taking data based on the user login
     * 
     * @return array
     */
    public static function getRoles()
    {
        if(PermissionRepository::isAdministrator())
            return Role::with('permissions')->get();
            
        return Auth::user()->getRoles();
    }
    
    /**
     * save data to storage update or insert
     * 
     * @return boolean
     */
    public static function save($data)
    { 
        $permissions = mPermission::find($data['group']);
        if(!$permissions)
        {
            self::$error = 'no  permission to inserted';
            return false;
        }
        
        $role = new RolesRepository(null, $data['role']);
        if(!$role->store())
            return false;

        $role->getModel()->permissions()->sync($permissions);
        $role->getModel()->permission = $permissions;
        
        return $role->getModel();
    }
}
