<?php

namespace App\Http\Controllers;

use App\Repositories\PermissionRepository;
use App\Http\Requests\PermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionsController extends Controller
{
    
    public function index()
    {
        return response()->json(array('data'=> Permission::getPermissions(), 'permission' => Permission::getStatusPermission('role.save')));
    }
    
    /**
     * data save to storage
     * 
     * @param PermissionRequest $request
     * @return mixed
     */
    public function save(PermissionRequest $request)
    {
        $request->validated();
        $pemission = PermissionRepository::save($request->input('permission'));
        if($pemission)
            return response()->json(array('status' => 'success' , 'data' => $pemission));
        
        return response()->json(['status' => 'error', 'message' => PermissionRepository::error()],400);
    }
    
    /**
     * delete transaction by id 
     * 
     * 
     * @param int $id, the primary id to be detele
     * @return mixed
     */
    public function delete($id)
    {
        $permission = new PermissionRepository($id);
        if($permission->deleteById())
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);
        
        return response()->json(['status' => 'error', 'message' => $permission->error()],400);
    }
}
