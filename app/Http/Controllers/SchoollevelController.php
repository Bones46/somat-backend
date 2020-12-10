<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SchoolLevelRequest;
use App\Repositories\SchoolLevelRepository;
use App\Repositories\PermissionRepository;

class SchoollevelController extends Controller
{
    protected $model;


    /**
     *  the constructor
     * 
     * @param SchoolLevelRepository $schoollevel
     */
    public function __construct(SchoolLevelRepository $schoollevel) {
        $this->model = $schoollevel ;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(array(
          'data'          => $this->model->getSchoollevel(), 
          'permission'    => PermissionRepository::getStatusPermission())
        );
    }
    
    /**
     * retrieve a listing of the active resouce 
     * 
     * @param Request $request
     * @return object 
     */
    public function Active()
    {
        return response()->json($this->model->getActive());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(SchoolLevelRequest $request)
    {
        $request->validated();
        
        $schoollevel = $this->model->save( $request->input('school_id'), $request->input('schoollevel'));
        if ($schoollevel)  
            return response()->json(['status' => 'success', 'data' => $schoollevel ]);
         
        return response()->json(['status' => 'error', 'message' => $this->model->error() ],400);        
    }

    /**
     * delete record school level
     * 
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        if(!$this->model->delete($id))
            return response()->json(['status' => 'error', 'message' => $this->model->error() ],400); 
        
        return response()->json(['status' => 'succed', 'message' => 'record has been deleted' ],400);        
    }

}
