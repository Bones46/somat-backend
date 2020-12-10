<?php

namespace App\Repositories;

use App\Models\lookupCode;
use Illuminate\Support\Facades\DB;

class LookupCodeRepository  extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'Lookupcode', $id, $data, null);
    }

    
    /**
     * retrieve All
     * 
     * @return array
     */
    public static function getLookupCode($lookupName, $active=false)
    {   
        $query = lookupCode::getByLookupName($lookupName);
        if($active)
            $query->active();
        
        return $query->get();
    }


    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public static function save($lookup, $data)
    {
        DB::beginTransaction();
        $result=array();
        
        foreach ($data as $item)
        {
            $item['lookup_name'] = $lookup['lookup_name'];
            if(!$item['effective_start_date'])
                $item['effective_start_date'] = $lookup['effective_start_date'];
            
            $lookupCode  = new LookupCodeRepository(null, $item);

            if(!$lookupCode->store())
            {
                DB::rollBack();
                return false;
            }
            
            $result[]=$lookupCode->getModel();
        }
        
        DB::commit();
        return $result;
    }
}