<?php

namespace App\Repositories;

use App\Models\Classes;
use Illuminate\Support\Facades\DB;

class ClassRepository  extends Repository
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'Classes', $id, $data);
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
            $class = new ClassesRepository(null, $item);

            if(!$class->store())
            {
                DB::rollBack();
                return false;
            }

            DB::commit();

            $result[] = $class->getModel();   
        }
        
        return $result;
    }
}