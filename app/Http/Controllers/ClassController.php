<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClassRequest;
use App\Repositories\ClassRepository;
use App\Repositories\PermissionRepository;

class ClassController extends Controller
{
    protected $model;
    public function __construct(ClassRepository $request) {
        $this->model= $request;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return response()->json(['status' => 'success', 'data' => classes::with(['classMajor','school','schoolYear','userProfile','lessonCategory','lessonTeachers','scheduleClasses','academicCalendars','studentClasses'])->get()]);
    
        return response()->json(array(
          'data'          => $this->model->getBySchool($request->input('school_id')), 
          'permission'    => PermissionRepository::getStatusPermission())
      );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(ClassRequest $request)
    {
        $request->validated();

        if ($class = $this->model->save($request->input('school_id'), $request->input('class')))  
            return response()->json(['status' => 'success', 'data' => $class]);
         
        return response()->json(['status' => 'error', 'message' => $this->model->error() ],400);
        
    }
    
    /**
     * retrieve active source
     * 
     * @param ClassRequest $request
     * @return type
     */
    public function active(Request $request)
    {
        return $this->model->getBySchool($request->input('school_id'), true);
    }
    
    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function find($id)
    {
        //$id = is_null($request->input('parent_id')) ? $id :$request->input('parent_id');
        
        $class= new ClassRepository($id);
        if($class->findByID())
            return response()->json(['status' => 'success', 'data' => $class->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $class->error()],400);
    }
    
     /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public function delete($id)
    {
        $class = new ClassRepository($id);
        if($class->deleteById())
            return response()->json(['status' => 'success', 'message' => 'data has been destroyed']);
        
        return response()->json(['status' => 'error', 'message' => $class->error()],400);
    }

}
