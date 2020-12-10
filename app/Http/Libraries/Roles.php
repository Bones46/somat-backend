<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\Auth;
use App\Permission as mPermission;
use App\Role;

class Roles  extends library //implements libraryContract
{
    protected $permission;

    /**
     * constructor
     *
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id ,$data = null)
    {
        parent::__construct($id,$data, 'Role');
    }

    /**
     * retrieve permission group
     * if administrator get all permission group, besides taking data based on the user login
     *
     * @return array
     */
    public static function getRoles()
    {
        if(Permission::isAdministrator())
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
        $role = new Roles(null, $data['role']);
        if(!$role->simpan())
            return false;

        $role->getModel()->permissions()->sync($permissions);
        $role->getModel()->permissions = $permissions;
        return $role->getModel();
    }

    /**
     *
     * @param type $id
     * @return boolean
     */
    public static function findById($id)
    {
        $role = Role::findById($id)->first();
        if(!$role)
        {
            self::$error = 'No Data Found';
            return false;
        }

        return $role;
    }

    /**
     * destroy single permission group on  role
     *
     * @param int $permissionGroupId
     * @return boolean
     */
    public function deletePermissionById($permissionGroupId)
    {
        $this->model = $this->findByID();
         if(!$this->getModel() )
        {
             self::$error = 'No Data Found To Be Deleted';
            return FALSE;
        }

        $permissionGroup = new PermissionGroup($permissionGroupId);
        if(!$permissionGroup->findByID())
        {
             self::$error = 'No Permission Found To Be Deleted';
            return FALSE;
        }

        $this->getModel()->wherePivot('permission_id', '=', $permissionGroupId);

        return true;
    }
}
