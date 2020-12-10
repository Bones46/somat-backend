<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;

class SchoolLevelRepository  extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'Schoollevel', $id, $data, null);
    }
    
    /**
     * retrieve All School Level 
     * 
     * @return array
     */
    public function getSchoolLevel()
    {       
        return $this->model->get();
    }

    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public function save($school_id, $data)
    {
        $return = array();
        
        DB::beginTransaction();
        foreach( $data as $item)
        {
            $item['school_id'] = $school_id;
            $schoollevel  = new SchoolLevelRepository(null, $item);
             
            if(!$schoollevel->store())
            {
                DB::rollBack();
                return false;
            }
            
            $return[] = $schoollevel->getModel();
        }
                
        DB::commit();
            
        return $return;  
    }
    
    /**
     * delete record from storage
     * 
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $this->id = $id;
        
        if(!$this->findByID())
            return false;
        
        if($this->model->getOneBySchoolLevel($id)->count() > 0)
        {
            self::$error = 'cannot delete, the record used by another transaction';
            return false;
        }
        
       return $this->destroy();
    }
}