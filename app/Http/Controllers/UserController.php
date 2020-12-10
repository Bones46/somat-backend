<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use App\Http\Requests\UserRequest;
use App\Http\Resources\GlobalCollection;
use  App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = user::with(['userProfile','userProfile.location'])->orderBy('created_date', 'DESC');
        if (request()->k != '' && request()->q != '') {
            $users = $users->where(request()->k, 'LIKE', '%' . request()->q . '%');
        }
        return response()->json(['status' => 'success', 'data' => $users->paginate(10)]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $validated = $request->validated();
        $user = new UserRepository($request->all());

        if ($user->save()) 
            return response()->json(['status' => 'success','data'=> $user->getModel() ], 201);
        
        return response()->json(['status' => 'error', 'message' => $user->error() ], 500);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = user::with(['userProfile','userProfile.location'])->find($id);

        if ($user) {
            return response()->json(['status' => 'success', 'data'=> $user]);
        }

        return response()->json(['status' => 'error', 'message' => 'Data not found'], 404);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $validated = $request->validated();

        $user = user::find($id);

        if ($user) {
            $data = $request->all();
            $data['password'] = bcrypt($data['password']);
            $user->update($data);
            return response()->json(['status' => 'success', 'message' => 'Data has been updated']);
        }

        return response()->json(['status' => 'error', 'message' => 'Cannot updating data'], 400);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = user::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
        }

        return response()->json(['status' => 'error', 'message' => 'Cannot deleting data'], 400);
    }
}
