<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\Auth;
use App\Models\Templates as mTemplates;
use App\Http\Libraries\Permission;

class TemplatesRepository  extends library //implements libraryContract
{ 
    protected $permission;
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id ,$data = null) 
    {
         if(!is_null($data) && array_key_exists('permission',$data))
            $this->permission = $data['permission'];

        $data = is_null($data) ? $data : $data['template'];
        
        parent::__construct($id, $data, 'Templates');
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
        $template = new Templates(null, $data);
        if(!$template->simpan())
        {
            self::$error = $template->error();
            return false;
        }        
        
        // save permission
        if(!is_null($template->permission))
            $template->permission=Permission::save($template->permission, $template->getModel()->template_id);
        
        $template->getModel()['permissions'] = $template->permission;
        
        return $template->getModel();
        
    }
    
    /**
     * 
     * @param int $id
     * @return boolean
     */
    public static function getById($id)
    {
        $template = mTemplates::findByID($id)->get();
        if(!$template)
        {
            self::$error = 'No Data Found';
            return false;
        }
        
        return $template;
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
