<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
USE App\Http\Requests\OrganizationRequest;
use App\Repositories\OrganizationRepository;

class OrganizationController extends Controller
{
    //TODO active source 
    
    /** 
     *
     * @var type
     */
    protected $model;

    /**
     * the constructor
     * 
     * @param OrganizationRepository $OrganizationRepository
     */
    public function __construct(OrganizationRepository $repository) {
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
    public function save( OrganizationRequest $request)
    {
        $request->validated();
        
        if(!$oranization = $this->model->save($request->input('school_id'), $request->input('organization')))
            return response()->json(['status' => 'error', 'messaget' => $this->model->error()]);
        
        return response()->json(['status' => 'success', 'data'=> $oranization]); 

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $org = new OrganizationRepository($id);
        if ($org->deleteById()) 
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
     
        return response()->json(['status' => 'error', 'message' => $org->error()],400);
    }
}
