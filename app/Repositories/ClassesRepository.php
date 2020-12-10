<?php

namespace App\Repositories;

use App\Models\Classes;
use App\Models\Mappingcode;
use Illuminate\Support\Facades\DB;

class ClassesRepository  extends Repository //implements libraryContract
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'Classes', $id, $data, null);
    }

    /**
     * retrieve All Class Major 
     * 
     * @return array
     */
    public static function getClasses()
    {       
        return Classes::active()->get();
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
        $classes  = new ClassesRepository(null, $data['classes']);

        if(!$classes->simpan())
        {
            DB::rollBack();
            return false;
        }
                
        DB::commit();
            
        return $classes->getModel();   
    }
 
 
}