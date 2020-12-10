<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LessonTimeRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\LessonTimeRepository;

class LessonTimeController extends Controller
{
    protected $model;
    
    /**
     * the constructor
     * 
     * @param LessonRepository $LessonRepository
     */
    public function __construct(LessonTimeRepository $LessonTimeRepository) {
        $this->model = $LessonTimeRepository;
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
    public function save(LessonTimeRequest $request)
    {
        $request->validated();
        if($lesson = $this->model->save($request->input('school_id'), $request->input('lessontime')))
            return response()->json(['status' => 'success', 'data' => $lesson]);
        
        return response()->json(['status' => 'error', 'message' => $this->model->error()],400);
    }
    
    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function find(Request $request ,$id)
    {
        
        $lesson = new LessonTimeRepository($id);
        if($lesson->findByID())
            return response()->json(['status' => 'success', 'data' => $lesson->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $lesson->error()],400);
    }
    
    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function delete($id)
    {
        $lesson = new LessonTimeRepository($id);
        if($lesson->deleteById())
            return response()->json(['status' => 'success', 'message' => 'data has been deleted']);
        
        return response()->json(['status' => 'error', 'message' => $lesson->error()],400);
    }
}
