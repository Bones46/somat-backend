<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LessonCategoryRepository  extends Repository //implements libraryContract
{ 
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'lessoncategory', $id, $data);
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
        $result = array();
        foreach($data as $item)
        {
            $item['school_id'] = $school_id;
            $cat = new LessonCategoryRepository(null, $item);
            if(!$cat->store())
            {
                $this->setErrorMessage($cat->error());
                DB::rollback();
                return false;
            }
            $result[] = $cat->getModel();
        }
        
        DB::commit();
        return $result;
    }
}
