<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AcademicYearRequest;
use App\Repositories\AcademicYearRepository;
use App\Repositories\PermissionRepository;

class AcademicYearController extends Controller
{
    protected $model;
    public function __construct(AcademicYearRepository $academic) {
        $this->model = $academic;
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(AcademicYearRequest $request)
    {
        $request->validated();
        
        $school_id = $request->input('school_id');

        if ($this->model->save($school_id, $request->all()))  
            return response()->json(['status' => 'success', 'data' => $this->model->getModel() ]);
         
        return response()->json(['status' => 'error', 'message' => $this->model->error() ],400);        
    }


    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function find(Request $request ,$id)
    {
        
        $schoolyear = new AcademicYearRepository($id);
        if($schoolyear->findByID())
            return response()->json(['status' => 'success', 'data' => $schoolyear->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $schoolyear->error()],400);
    }
    
     /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function Active(Request $request)
    {
        return response()->json($this->model->getBySchool($request->input('school_id'), true));
    }
}
