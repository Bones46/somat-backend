<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\RegionRepository;
use Illuminate\Support\Facades\Route;

class RegionController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = RegionRepository::getRegion($id, Route::currentRouteName());
        return response()->json(['status' => 'success', 'data' => $data ]);
    }
 
    
}
