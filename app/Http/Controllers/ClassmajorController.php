<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClassMajorRequest;
use App\Repositories\ClassmajorRepository;
use App\Repositories\PermissionRepository;

class ClassmajorController extends Controller
{
    protected $model;

    /**
     * the constructor
     * 
     * @param ClassMajorRequest $classMajor
     */
    public function __construct(ClassmajorRepository $classMajor) {
        $this->model = $classMajor;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @return type
     */
    public function active(Request $request)
    {
        return  response()->json($this->model->getBySchool($request->input('school_id'), true));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(ClassMajorRequest $request)
    {
        $request->validated();

        if ($classmajor = $this->model->save($request->input('school_id'), $request->input('classmajor')))  
            return response()->json(['status' => 'success', 'data' => $classmajor ]);
         
        return response()->json(['status' => 'error', 'message' => $this->model->error() ],400);        
    }

    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public function find(Request $request ,$id)
    {
        $classmajor = new ClassmajorRepository($id);
        if($classmajor->findByID())
            return response()->json(['status' => 'success', 'data' => $classmajor->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $classmajor->error()],400);
    }
    
    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function delete(Request $request ,$id)
    {
        
        $classmajor = new ClassmajorRepository($id);
        if($classmajor->deleteById())
            return response()->json(['status' => 'success', 'data' => $classmajor->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $classmajor->error()],400);
    }
}
