<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\studentclass;
use App\Http\Requests\StudentClassMapRequest;
use App\Repositories\StudentClassMapRepository;
use App\Repositories\PermissionRepository;

class StudentclassmapController extends Controller
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
          'data'          => StudentClassMapRepository::getStudentClassMap(), 
          'permission'    => PermissionRepository::getStatusPermission())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(StudentClassMapRequest $request)
    {
        $validated = $request->validated();

        if ($studentclassmap = StudentClassMapRepository::save($request->all()))  
            return response()->json(['status' => 'success', 'data' => $studentclassmap ]);
         
        return response()->json(['status' => 'error', 'message' => StudentClassMapRepository::error() ],400);
        
    }

    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function findById(Request $request ,$id)
    {
        //$id = is_null($request->input('parent_id')) ? $id :$request->input('parent_id');
        
        $studentclassmap = new StudentClassMapRepository($id);
        if($studentclassmap->findByID())
            return response()->json(['status' => 'success', 'data' => $studentclassmap->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $studentclassmap->error()],400);
    }

}
