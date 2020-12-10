<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\Auth;
use App\Permission as mPermission;
use Illuminate\Support\Facades\DB;
use App\PermissionGroup;
use App\Http\Libraries\Permission;

class PermissionGroups  extends library //implements libraryContract
{ 
    protected $permission;

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id ,$data = null) 
    {
        if(!is_null($data) && array_key_exists('permissions',$data))
            $this->permission = $data['permissions'];

        $data = is_null($data) ? $data : $data['group'];
            
        parent::__construct($id,$data, 'PermissionGroup');
    }
    
    /**
     * retrieve permission group 
     * if administrator get all permission group, besides taking data based on the user login
     * 
     * @return array
     */
    public static function getPermissionGroups()
    {
        if(Permission::isAdministrator())
            return PermissionGroup::with('permission')->get();
            
        return Auth::user()->getPermissionGroups();
    }
    
    /**
     * save data to storage update or insert
     * 
     * @return boolean
     */
    public function save()
    { 
        $permissions = mPermission::find($this->permission);
        if(!$permissions)
        {
            $this->error = 'no permission to inserted';
            return false;
        }
        
        if(!$this->simpan())
            return false;

        $this->getModel()->Permission()->sync($permissions);
        $this->getModel()->permission = $permissions;
        
        return true;
    }
    
    /**
     * destroy data permission group from storage by primary id
     * 
     * @return boolean
     */
    public function deleteById()
    {
        $this->model = $this->findByID();
         if(!$this->getModel() )
        {
            $this->error = 'No Data Found To Be Deleted';
            return FALSE;
        }
        
        $this->getModel()->permission()->detach();
        $this->getModel()->delete();
        
        return TRUE;
    }
    
    /**
     * destroy single permission on group permission
     * 
     * @param int $permissionId
     * @return boolean
     */
    public function deletePermissionById($permissionId)
    {
        $this->model = $this->findByID();
         if(!$this->getModel() )
        {
            $this->error = 'No Data Found To Be Deleted';
            return FALSE;
        }
        
        $permission = new Permission($permissionId);
        if(!$permission->findByID())
        {
            $this->error = 'No Permission Found To Be Deleted';
            return FALSE;
        }
        
        $this->getModel()->wherePivot('permission_id', '=', $permissionId);
        
        return true;
    }
}
