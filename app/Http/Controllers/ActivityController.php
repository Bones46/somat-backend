<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ActivityRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\ActivityRepository;

class ActivityController extends Controller
{
    protected $model;
    
    /**
     * constructor
     * 
     * @param ActivityRepository $activityRepository
     */
    public function __construct( ActivityRepository $activityRepository) {
        $this->model = $activityRepository;
    }
   
    /**
     * 
     * @return mixed
     */
    public function index(Request $request)
    {
        
        return response()->json(array(
                                    'data'          => $this->model->getBySchool($request->input('school_id')), 
                                    'permission'    => PermissionRepository::getStatusPermission())
                                );
    }
    
    /**
     * 
     * @param Request $request
     * @return mixed
     */
    public function active(Request $request)
    {
        
        return response()->json( $this->model->getBySchool($request->input('school_id'), true));
    }
    
     /**
     * save data to storage
     * 
     * @param PermissionGroupsRequest $request
     * @return array
     */
    public function save(ActivityRequest $request)
    {
        $request->validated();
        return response()->json( $request->input('school_id'));
         
         
//        if($lesson = $this->model->save($request->input('school_id'), $request->input('activity')))
//            return response()->json(['status' => 'success', 'data' => $lesson]);
//        
//        return response()->json(['status' => 'error', 'message' => $this->model->error()],400);
    }
    
    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function find(Request $request ,$id)
    {
        
        $activity = new ActivityRepository($id);
        if($activity->findByID())
            return response()->json(['status' => 'success', 'data' => $activity->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $activity->error()],400);
    }
    
    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function delete($id)
    {
        $activity = new ActivityRepository($id);
        if($activity->deleteById())
            return response()->json(['status' => 'success', 'message' => 'data has been deleted']);
        
        return response()->json(['status' => 'error', 'message' => $activity->error()],400);
    }
}
