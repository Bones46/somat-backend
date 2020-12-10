<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eventtypes;
use App\Http\Requests\EventTypesRequest;

class EventtypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => eventtypes::with('events')->get()]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventTypesRequest $request)
    {        
        $validated = $request->validated();
        if (eventtypes::create($request->all())) {
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
        $eventtypes = eventtypes::with('events')->find($id);
        
        if ($eventtypes) {
            return response()->json(['status' => 'success', 'data'=> $eventtypes]);
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
    public function update(EventTypesRequest $request, $id)
    {
        $validated = $request->validated();

        $eventtypes = eventtypes::find($id);

        if ($eventtypes) {
            $eventtypes->update($request->all());
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
        $eventtypes = eventtypes::find($id);
        if ($eventtypes) {
            $eventtypes->delete();
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
          }
     
          return response()->json(['status' => 'error', 'message' => 'Cannot deleting data'],400);
    }
}
