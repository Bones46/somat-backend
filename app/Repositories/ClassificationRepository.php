<?php

namespace App\Repositories;

use App\Models\classification;
use Illuminate\Support\Facades\DB;

class ClassificationRepository extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'Classification', $id, $data, null);
    }

    
    /**
     * retrieve All Class Major 
     * 
     * @return array
     */
    public static function getClassification($name)
    {   
        switch ($name) {
            case "teacher":
                return Classification::where('classification_name','Pendidik')
                        ->with('employees.userprofile')
                        ->get();
                           // ['classification_id', 'school_id', 'classification_name', 'description','active_flag']);
                break;
            case "classification":
                return Classification::get();

                break;
        }
        
    }


    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public static function save($data)
    {
        DB::beginTransaction();

        $classification  = new ClassificationRepository(null, $data['classification']);

        if(!$classification->simpan())
        {
            DB::rollBack();
            return false;
        }
                
        DB::commit();
            
        return $classification->getModel();  
    }

    
}