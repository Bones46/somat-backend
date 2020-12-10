<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SchoolRepository;
use App\Http\Requests\SchoolProfileRequest;

class SchoolController extends Controller
{
    protected $model;

    public function __construct(SchoolRepository $school) 
    {
        $this->model = $school;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(['data' => $this->model->with(['location'])->paginate(request()->row)]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(SchoolProfileRequest $request)
    {

        $request->validated();
        if($this->model->save($request))
            return response()->json(['status' => 'success', 'data' => $this->school->getModel() ]);
            
        return response()->json(['status' => 'error', 'message' => $this->school->error() ],500);
         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find($id)
    {
        if ($this->model->getById($id)) 
            return response()->json(['status' => 'success','data'=> $this->school->getModel()]);
          
        return response()->json(['status' => 'error', 'message' => $this->school->error()],404);
    }

}
