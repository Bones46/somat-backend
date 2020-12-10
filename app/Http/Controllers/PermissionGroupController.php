<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Libraries\PermissionGroups;
use \App\Http\Requests\PermissionGroupsRequest;

class PermissionGroupController extends Controller
{
    //
    public function index()
    {
        return response()->json(PermissionGroups::getPermissionGroups()); 
    }
    
    /**
     * save data to storage
     * 
     * @param PermissionGroupsRequest $request
     * @return array
     */
    public function save(PermissionGroupsRequest $request)
    {
        $request->validated();
        
        $group = new PermissionGroups(null, $request->all());
        if($group->save())
            return response()->json(['status' => 'success', 'data' => $group->getModel()]);
        
        return response()->json(['status' => 'error', 'message' => $group->error()],400);
    }
    
    /**
     * delete transaction from storageby id  
     * 
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        $group = new PermissionGroups($id);
        
        if($group->deleteById())
            return response()->json(['status' => 'success', 'message' =>  'Data has been deleted']);
        
        return response()->json(['status' => 'error', 'message' => $group->error()],400);
    }
}
