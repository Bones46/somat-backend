<?php

namespace App\Repositories;
use Illuminate\Support\Facades\DB;

class LessonTimeRepository  extends Repository
{ 
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'lessontime', $id, $data);
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
            $lessonTime  = new LessonTimeRepository(null, $item);
            if(!$lessonTime->store())
            {
                $this->setErrorMessage($lessonTime->error());
                DB::rollBack();
                return false;
            }
            
            $result[]=$lessonTime->getModel();
        }
        
        DB::commit();  
        return  $result;
    }
}
