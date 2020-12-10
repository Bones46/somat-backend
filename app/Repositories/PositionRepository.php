<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class PositionRepository  extends Repository
{ 
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'position', $id, $data);
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
            $org = new PositionRepository(null, $item);
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
    
    /**
     * retrieve positiion by organization
     * 
     * @param type $school_id
     * @param type $org_id
     * @return type
     */
    public function getByOrganization($school_id, $org_id)
    {
        return $this->model->getByOrganization($school_id, $org_id);
    }
}
