<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\studentclass;
use App\Http\Requests\StudentClassRequest;
use App\Repositories\StudentClassRepository;
use App\Repositories\PermissionRepository;

class StudentclassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return response()->json(['status' => 'success', 'data' => studentclass::with(['classes','student','childStudentClass','parentStudentClass'])->get()]);
        return response()->json(array(
          'data'          => StudentClassRepository::getStudentClass(), 
          'permission'    => PermissionRepository::getStatusPermission())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(StudentClassRequest $request)
    {
        $validated = $request->validated();

        if ($studentclass = StudentClassRepository::save($request->all()))  
            return response()->json(['status' => 'success', 'data' => $studentclass ]);
         
        return response()->json(['status' => 'error', 'message' => StudentClassRepository::error() ],400);
        
    }

    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function findById(Request $request ,$id)
    {
        //$id = is_null($request->input('parent_id')) ? $id :$request->input('parent_id');
        
        $studentclass = new StudentClassRepository($id);
        if($studentclass->findByID())
            return response()->json(['status' => 'success', 'data' => $studentclass->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $studentclass->error()],400);
    }


}
