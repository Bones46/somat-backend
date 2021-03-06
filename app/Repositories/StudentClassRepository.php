<?php

namespace App\Repositories;

use App\Models\StudentClass;
use Illuminate\Support\Facades\DB;

class StudentClassRepository  extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'StudentClass', $id, $data, null);
    }

    /**
     * retrieve All Class Major 
     * 
     * @return array
     */
    public static function getStudentClass()
    {       
        return StudentClass::get();  //  active()->get();
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
        $classes  = new StudentClassRepository(null, $data['studentclass']);

        if(!$classes->simpan())
        {
            DB::rollBack();
            return false;
        }
                
        DB::commit();
            
        return $classes->getModel();   
    }

    

}