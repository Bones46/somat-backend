<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\JobRequest;
use App\Repositories\JobRepository;
use App\Repositories\PermissionRepository;

class JobController extends Controller
{
    
    protected $model;
    
    /**
     * 
     * @param PositionRepository $repository
     */
    public function __construct(JobRepository $repository)
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
        return response()->json(['data' => $this->model->getBySchool($request->input('school_id')),  
                                 'permission'    => PermissionRepository::getStatusPermission()]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(JobRequest $request)
    {
        $request->validated();
        
        if(!$position = $this->model->save($request->input('school_id'), $request->input('job')))
            return response()->json(['status' => 'error', 'messaget' => $this->model->error()]);
        
        return response()->json(['status' => 'success', 'data'=> $position]); 
    }
    
    /**
     * 
     * @param type $org_id
     * @return type
     */
    public function position(Request $request, $org_id)
    {
        return response()->json($this->model->getByPosition($request->input('school_id'), $org_id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $job = new JobRepository($id);
        if ($job->deleteById()) 
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
     
        return response()->json(['status' => 'error', 'message' => $job->error()],400);
    }
}
