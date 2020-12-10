<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\userprofile;
use App\Http\Requests\UserProfileRequest;

class UserprofileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['status' => 'success', 'data' => userprofile::with(['location','user','lessonTeachers','scheduleClasses','fromNotifications','toNotifications','schools','personIdMappings','joinIdMappings','userNotif','students','employees','classes'])->get()]);
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserProfileRequest $request)
    {
        $validated = $request->validated();

        if (userprofile::create($request->all())) {
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
        $userprofile = userprofile::with(['location','user','lessonTeachers','scheduleClasses','fromNotifications','toNotifications','schools','personIdMappings','joinIdMappings','userNotif','students','employees','classes'])->find($id);

        if ($userprofile) {
            return response()->json(['status' => 'success', 'data'=> $userprofile]);
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
    public function update(UserProfileRequest $request, $id)
    {
        $validated = $request->validated();

        $userprofile = userprofile::find($id);
        
        if ($userprofile) {
            $userprofile->update($request->all());
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
        $userprofile = userprofile::find($id);
        if ($userprofile) {
            $userprofile->delete();
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
          }
     
          return response()->json(['status' => 'error', 'message' => 'Cannot deleting data'],400);
    }
}
