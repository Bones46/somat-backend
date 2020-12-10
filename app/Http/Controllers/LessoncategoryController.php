<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LessonCategoryRequest;
use \App\Repositories\LessonCategoryRepository;
use App\Repositories\PermissionRepository;

class LessoncategoryController extends Controller
{
    
    protected $model;
    
    public function __construct(LessonCategoryRepository $category)  {
        $this->model = $category;
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
     * 
     * display a listing the active resource
     * 
     * @param  Illuminate\Http\Request
     * @return mixed  \Illuminate\Http\Response
     */
    public function Active(Request $request)
    {
         return response()->json($this->model->getBySchool($request->input('school_id'), true));
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(LessonCategoryRequest $request)
    {        
        $request->validated();
        
        $cat = $this->model->save($request->input('school_id'), $request->input('category'));
        if (!$cat)
            return response()->json(['status' => 'error', 'message' => $this->model->error() ],500);
        
        return response()->json(['status' => 'success', 'data' => $cat ],201);
    }
}
