<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\classification;
use App\Repositories\ClassificationRepository;
use App\Repositories\PermissionRepository;
use Illuminate\Support\Facades\Route;

class ClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(array(
          'data'          => ClassificationRepository::getClassification(Route::currentRouteName()), 
          'permission'    => PermissionRepository::getStatusPermission())
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request)
    {
        //$validated = $request->validated();

        if ($classification = ClassificationRepository::save($request->all()))  
            return response()->json(['status' => 'success', 'data' => $classification ]);
         
        return response()->json(['status' => 'error', 'message' => ClassificationRepository::error() ],400);        
    }


    /**
     * 
     * @param int $id , primary key of lesson
     * @return array
     */
    public  function findById(Request $request ,$id)
    {
        
        $classification = new ClassificationRepository($id);
        if($classification->findByID())
            return response()->json(['status' => 'success', 'data' => $classification->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $classification->error()],400);
    }

}
