<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class JobRepository  extends Repository
{ 
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'jobs', $id, $data);
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
            $job = new JobRepository(null, $item);
            if(!$job->store())
            {
                $this->setErrorMessage($job->error());
                DB::rollback();
                return false;
            }
            $result[] = $job->getModel();
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
    public function getByPosition($school_id, $org_id)
    {
        return $this->model->getByPosition($school_id, $org_id);
    }
}
