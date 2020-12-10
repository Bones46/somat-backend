<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\locations;
use App\Http\Requests\LocationsRequest;

class LocationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => locations::with(['schools','userProfiles'])->get()]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocationsRequest $request)
    {
        $validated = $request->validated();

        if (locations::create($request->all())) {
            return response()->json(['status' => 'success', 'message' => 'Data has been created' ],201);
          } else {
            return response()->json(['status' => 'error', 'message' => 'Internal Server Error' ],500);
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $locations = locations::with(['schools','userProfiles'])->find($id);

        if ($locations) {
            return response()->json(['status' => 'success', 'data'=> $locations]);
          }
   
          return response()->json(['status' => 'error', 'message' => 'Data not found'],404);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LocationsRequest $request, $id)
    {
        $validated = $request->validated();

        $locations = locations::find($id);

        if ($locations) {
            $locations->update($request->all());
            return response()->json(['status' => 'success', 'message' => 'Data has been updated']);
          }
   
          return response()->json(['status' => 'error', 'message' => 'Cannot updating data'],400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $locations = locations::find($id);
        if ($locations) {
            $locations->delete();
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
          }
     
          return response()->json(['status' => 'error', 'message' => 'Cannot deleting data'],400);
    }
}
