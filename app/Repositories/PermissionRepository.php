<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\Permission as mPermission;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;

class PermissionRepository  extends Repository //implements libraryContract
{
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null,$permission = null) {
        parent::__construct('Permission', $id, $permission);
    }
    
    /**
     * get All Permission of User
     * 
     * @return Mixed
     */
    public static function getPermissions()
    {
        if(static::isAdministrator())
            return mPermission::all();
            
        return Auth::user()->getPermissions();
    }
    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public static function save($data, $template_id= null)
    { 
        $result = array();
        DB::beginTransaction();
        foreach($data as $key => $item){

            if(!is_null($template_id) && !array_key_exists('template_id', $item))
                $item['template_id'] = $template_id;

            $permission = new PermissionRepository(null, $item);
             if(!$permission->store())
             {
                 DB::rollBack();
                 return false;
             }

            $result[$key]= $permission->getModel();
        }

        DB::commit();
        return $result;
    }
    
    /**
     * get status route of page
     * 
     * @param string $slug, route alias
     * 
     * @return array
     */
    public static function getStatusPermission()
    {
        $permissions = mPermission::getBySlug(Request::route()->getName());

        if($permissions->count() === 0)
            return 'no setup permission route name '.Request::route()->getName();
        
        
        $result = array();
        $data = array();
        
        $allows = Auth::user()->getPermissionByTemplate($permissions->template->template_id)->pluck('slug');
        $allows = static::isAdministrator() ? $permissions->pluck('slug') : $allows;
        
        foreach($allows as $allow)
            $data[$allow] = $allow;
        
        // collect status permission of user 
        foreach($permissions->template->permissions as $permission)
            $result[$permission->slug]  = array_key_exists($permission->slug, $data);
        
         return $result;
    }

    /**
     * Roles allowed to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function allow($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (!Auth::user()->inRoles($roles)) {
            return static::error();
        }
    }

    /**
     * Don't check permission.
     *
     * @return bool
     */
    public static function free()
    {
        return true;
    }

    /**
     * Roles denied to access.
     *
     * @param $roles
     *
     * @return true
     */
    public static function deny($roles)
    {
        if (static::isAdministrator()) {
            return true;
        }

        if (Auth::user()->inRoles($roles)) {
           return  static::error();
        }
    }

    /**
     * If current user is administrator.
     *
     * @return mixed
     */
    public static function isAdministrator()
    {
        return Auth::user()->isRole('administrator');
    }
    
    
}
