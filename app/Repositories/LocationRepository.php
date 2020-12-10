<?php

namespace App\Repositories;

use App\Models\locations;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserProfileRepository;

class LocationRepository  extends Repository //implements libraryContract
{
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id =null ,$data = null) 
    {
        parent::__construct( 'locations', $id, $data);
    }
    
    /**
     * retrieve permission group 
     * if administrator get all permission group, besides taking data based on the user login
     * 
     * @return array
     */
    public function getLocation($paginate)
    {
        return locations::all()->paginate($paginate);
    }
    
    /**
     * 
     * @param type $villageId
     * @param type $neigbhourhood
     * @param type $hamlet
     * @param type $addrees
     * @return type
     */
    public function findOrCreate($villageId,$neighbourhood, $hamlet, $address)
    {
        return $this->getModel()->findOrCreate($villageId,$neighbourhood, $hamlet, $address);
    }
    
    
}
