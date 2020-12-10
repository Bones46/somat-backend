<?php

namespace App\Repositories;

use App\Models\Classmajor;
use Illuminate\Support\Facades\DB;

class ClassmajorRepository  extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'classmajor', $id, $data, null);
    }
    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public function save($school_id, $data)
    {
        DB::beginTransaction();
        
        // get class level by school
        $school = new SchoolRepository($school_id);
        if(!$school->findByID())
        {
            $this->setErrorMessage($school->error());
            DB::rollBack();
            return false;
        }
        
        if(!$school->getModel()->schoolLevel)
        {
            $this->setErrorMessage('school doesn have academic level');
            DB::rollBack();
            return false;
        }
        
        $result = array();
        foreach ($data as $item)
        { 
            $item['school_level_id'] = $school->getModel()->schoolLevel->school_level_id;
            $item['school_id'] = $school->getModel()->school_id;
            
            $classmajor  = new ClassmajorRepository(null, $item);
            if(!$classmajor->store())
            {
                $this->setErrorMessage($classmajor->error());
                DB::rollBack();
                return false;
            }
            
            $result[] = $classmajor->getModel();
            
        }
        DB::commit();
            
        return $result;   
    }
}
