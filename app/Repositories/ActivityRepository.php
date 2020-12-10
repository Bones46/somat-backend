<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;

class ActivityRepository  extends Repository
{ 
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'activity', $id, $data);
    }
    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public  function save($school_id, $data)
    {
        DB::beginTransaction();
        
        $result = array();
        foreach($data as $item)
        {
            $item['school_id'] = $school_id;
            $activity  = new ActivityRepository(null, $item);
            if(!$activity->store())
            {
                $this->setErrorMessage($activity->error());
                DB::rollBack();
                return false;
            }
            
            $result[]=$activity->getModel();
        }
        
        DB::commit();  
        return  $result;
    }
}
