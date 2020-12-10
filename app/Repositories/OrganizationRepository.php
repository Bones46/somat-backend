<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class OrganizationRepository  extends Repository //implements libraryContract
{ 
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'organization', $id, $data);
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
            $org = new OrganizationRepository(null, $item);
            if(!$org->store())
            {
                $this->setErrorMessage($org->error());
                DB::rollback();
                return false;
            }
            $result[] = $org->getModel();
        }
        
        DB::commit();
        return $result;
    }
}
