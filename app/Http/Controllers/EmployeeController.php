<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EmployeeRequest;
use App\Repositories\PermissionRepository;
use App\Repositories\EmployeeRepository;

class EmployeeController extends Controller
{
    protected $model;

    public function __construct(EmployeeRepository $repository) 
    {
        $this->model = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(['data' => $this->model->getBySchoolWith($request->input('school_id'),array('profile','classification')), 
                                 'permission' => PermissionRepository::getStatusPermission()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 
    public function save(EmployeeRequest $request)
    {

        $request->validated();
        
        if($this->model->Register($request))
             return response()->json(['status' => 'succes', 'data' =>$this->model->getModel() ],201);
        
         return response()->json(['status' => 'error', 'message' => $this->model->error() ],500);
          
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    public function find(Request $request, $id)
    {
        if ( $employee = $this->model->getByIdWith($request->input('school_id'), $id, array('profile','classification'))) {
            return response()->json(['status' => 'success', 'data'=> $employee]);
          }
   
          return response()->json(['status' => 'error', 'message' => 'Data not found'],404);
    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function teacher(Request $request)
    {
        return response()->json($this->model->getTeacher($request->input('school_id')));
    }
}
