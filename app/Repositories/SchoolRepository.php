<?php

namespace App\Repositories;

use App\Repositories\LocationRepository;
use Carbon\Carbon;
use App\Models\school;
use Illuminate\Support\Facades\DB;

class SchoolRepository extends Repository
{
    
    /**
     * the constructor
     * 
     * @param int $id
     * @param array $data
     */
    public function __construct($id = null, $data = null) {
        parent::__construct('school', $id, $data);
    }
    /**
     * 
     * @param \App\Repositories\request $request
     * @return boolean
     */
    public function save( $request)
    {
        DB::BeginTransaction();
        $paramlocation = $request->input('location');
        $school = $request->input('school');
        $location = (new LocationRepository())->findOrCreate($paramlocation['village_id'],$paramlocation['neighbourhood'], $paramlocation['hamlet'], $paramlocation['address']);
       
        $school['location_id'] = $location->location_id;
        
        $this->data = $school;
        if($this->isEmpty($this->data, 'school_id'))
            $this->data['effective_start_date'] = Carbon::now();
                
        if(!$this->store())
        {
            return false;
            DB::rollback();
        }
        
        DB::commit();
        $this->getModel()['location'] = $location;
        return true;
    }
    
    /**
     * find the specific resource
     * 
     * @param type $id
     */
    public function getById($id) 
    {
        $this->model = $this->getModel()->with('location')->find($id);
        if(!$this->model)
        {
            self::$error = 'no data found';
            return false;
        }
        
        return $this->getModel();
    }
    
    /**
     * 
     * @param type $schoollvl
     * @return type
     */
    public static function getOneBySchoolLevel($id)
    {
        return school::getBySchoolLevel($id)->first();
    }
}