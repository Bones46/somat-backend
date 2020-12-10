<?php

namespace App\Repositories;

use App\Models\StudentClassMap;
use Illuminate\Support\Facades\DB;

class StudentClassMapRepository  extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'StudentClassMap', $id, $data, null);
    }

    /**
     * retrieve All Class Major 
     * 
     * @return array
     */
    public static function getStudentClassMap()
    {       
        return StudentClassMap::get();  //  active()->get();
    }

    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public static function save($data)
    {
        //var_dump($data);die;
        DB::beginTransaction();
        
        //$array = array();
        $classes  = new StudentClassMapRepository(null, $data['studentclassmap']);

        if(!$classes->simpan())
        {
            DB::rollBack();
            return false;
        }
                
        DB::commit();
            
        return $classes->getModel();   //$data['group_flag'] === 'Y' ?$array : $lesson;
    }

    

}