<?php

namespace App\Repositories;

use App\Models\userprofile;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\DB;

class UserProfileRepository  extends Repository //implements libraryContract
{ 
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id =null ,$data = null) 
    {
        parent::__construct( 'userprofile', $id, $data);
    }
    
    /**
     * save data to storage
     * 
     * @return boolean
     */
    public function save()
    {
        return $this->store();
    }
    
    /**
     * 
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->data['user_id'] = $user_id;
    }
    
    /**
     * 
     * @param int $locationId
     */
    public function setLocationId($locationId)
    {
        $this->data['location_id'] = $locationId;
    }
}