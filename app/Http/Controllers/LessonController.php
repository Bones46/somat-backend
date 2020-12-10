<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LessonRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\LessonRepository;

class LessonController extends Controller
{
    protected $model;
    
    /**
     * the constructor
     * 
     * @param LessonRepository $LessonRepository
     */
    public function __construct(LessonRepository $LessonRepository) {
        $this->model = $LessonRepository;
    }
   
    /**
     * 
     * @return mixed
     */
    public function index(Request $request)
    {
        $lessonType = is_null($request->input('key')) ? LessonRepository::LESSON: $request->input('key');
        
        return response()->json(array(
                                    'data'          => $this->model->getLessonBySchool($request->input('school_id'),$lessonType), 
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
        $lessonType = is_null($request->input('key')) ? LessonRepository::LESSON: $request->input('key');
        
        return response()->json( $this->model->getActiveLessonBySchool($request->input('school_id'),$lessonType));
    }
    
     /**
     * save data to storage
     * 
     * @param PermissionGroupsRequest $request
     * @return array
     */
    public function save(LessonRequest $request)
    {
        $request->validated();
        if($lesson = $this->model->save($request->input('school_id'), $request->input('lesson')))
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
        $id = is_null($request->input('parent_id')) ? $id :$request->input('parent_id');
        
        $lesson = new LessonRepository($id);//LessonRepository::findById($id);
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
        
        $lesson = new LessonRepository($id);
        if($lesson->deleteById())
            return response()->json(['status' => 'success', 'message' => 'data has been deleted']);
        
        return response()->json(['status' => 'error', 'message' => $lesson->error()],400);
    }
}
