<?php

namespace App\Repositories;

use App\Models\Templates as mTemplates;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\DB;

class TemplatesRepository  extends Repository //implements libraryContract
{ 
    protected $permission;
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id =null ,$data = null) 
    {
        
        parent::__construct( 'Templates', $id, $data);
    }
    
    /**
     * retrieve permission group 
     * if administrator get all permission group, besides taking data based on the user login
     * 
     * @return array
     */
    public static function getTemplates()
    {
        return mTemplates::with('Permissions')->get();
    }
    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public static function save($data)
    {
        DB::beginTransaction();
        $template = new TemplatesRepository(null, $data['template']);
        
        if(!$template->store())
        {
            self::$error = $template->error();
            return false;
        }        
        
        // save permission
        if(array_key_exists('permission', $data) && !is_null($data['permission']))
        {
            if(!$permission=PermissionRepository::save($data['permission'], $template->getModel()->template_id))
            {
                return false;
                DB::rollBack();
            }
        }
        
        $template->getModel()['permissions'] = $permission;
        
        DB::commit();
        
        return $template->getModel();
        
    }

    /**
     * get error  failed process message
     * @return type
     */
    public static function getErrorMessage()
    {
        return self::$error;
    }
}
