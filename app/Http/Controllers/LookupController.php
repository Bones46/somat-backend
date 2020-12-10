<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\groupcode;
use App\Repositories\LookupRepository;
use App\Http\Requests\LookupRequest;
use App\Repositories\PermissionRepository;

class LookupController extends Controller
{
    
    protected $model;

    
    /**
     * the constructor
     * 
     * @param LookupRepository $repotisotry
     */
    public function __construct(LookupRepository $repository) {
        $this->model = $repository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return response()->json(array(
        'data'          => $this->model->getLookup(), 
        'permission'    => PermissionRepository::getStatusPermission())
      );

    }
    
    /**
     * 
     * @param Request $request
     * @return type
     */
    public function lookupCode(Request $request)
    {
        return $this->model->getLookupCode($request->input('name'), true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(LookupRequest $request)
    {
        $request->validated();

        if ($this->model->save($request->all()))  
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
        
        $groupcode = new GroupCodeRepository($id);
        if($groupcode->findByID())
            return response()->json(['status' => 'success', 'data' => $groupcode->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $groupcode->error()],400);
    }
    
}
