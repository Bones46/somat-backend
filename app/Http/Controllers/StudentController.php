<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StudentRequest;
use App\Repositories\StudentRepository;
use App\Repositories\PermissionRepository;

class StudentController extends Controller
{
    protected $student;

    public function __construct(StudentRepository $repository) 
    {
        $this->student = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(['data' => $this->student->getBySchoolWith($request->input('school_id'),array('profile', 'profile.family')), 
                                 'permission' => PermissionRepository::getStatusPermission()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
 
    public function save(StudentRequest $request)
    {

        $request->validated();
        
        if($this->student->Register($request))
             return response()->json(['status' => 'succes', 'data' =>$this->student->getModel() ],201);
        
        return response()->json(['status' => 'error', 'message' => $this->student->error() ],500);
          
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     
    public function show($id)
    {
        $student = $this->student->findByID();
        if ($student) 
            return response()->json(['status' => 'success', 'data'=> $student]);
        
        return response()->json(['status' => 'error', 'message' => 'Data not found'],404);
    }
}
