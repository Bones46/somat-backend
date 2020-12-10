<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\event;
use App\Http\Requests\EventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => event::with(['eventType','notifications'])->get()]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $validated = $request->validated();

        if (event::create($request->all())) {
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
        $event = event::with(['eventType','notifications'])->find($id);

        if ($event) {
            return response()->json(['status' => 'success', 'data'=> $event]);
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
    public function update(EventRequest $request, $id)
    {
        $validated = $request->validated();

        $event = event::find($id);

        if ($event) {
            $event->update($request->all());
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
        $event = event::find($id);
        if ($event) {
            $event->delete();
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
          }
     
          return response()->json(['status' => 'error', 'message' => 'Cannot deleting data'],400);
    }
}
