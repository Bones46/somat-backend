<?php

namespace App\Repositories;

use App\Models\GroupCode;
use Illuminate\Support\Facades\DB;
use App\Repositories\LookupCodeRepository;

class lookupRepository  extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'Lookup', $id, $data, null);
    }

    
    /**
     * retrieve All 
     * 
     * @return array
     */
    public  function getLookup()
    {       
        return $this->model->with('lookupCode')->get();
    }
    
    /**
     * 
     * @param type $lookupName
     * @param type $active
     * @return type
     */
    public function getLookupCode($lookupName, $active=false)
    {
        return LookupCodeRepository::getLookupCode($lookupName,$active);
    }

    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public function save($data)
    {
        
        DB::beginTransaction();
        $this->data = $data['lookup'];
        if(!$this->store())
            return false;
        
        $lookupCode = LookupCodeRepository::save($this->getModel(), $data['lookupcode']);
        if(!$lookupCode)
        {
            $this->setErrorMessage(LookupCodeRepository::error());           
            DB::rollBack();
            return false;
        }

        $this->model['lookup_code'] = $lookupCode;    
        DB::commit();
        return true;  
    }
  
}