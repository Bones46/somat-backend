<?php

namespace App\Http\Controllers;

use App\Repositories\RolesRepository;
use App\Http\Requests\RoleRequest;
use App\Http\Libraries\Permission;
use Illuminate\Support\Facades\Request;

class RoleController extends Controller
{
    public function index()
    {
      return response()->json(array('data'=>  RolesRepository::getRoles(), 'permission'=> Permission::getStatusPermission()));
    }

      /**
     * save data to storage
     *
     * @param PermissionGroupsRequest $request
     * @return array
     */
    public function save(RoleRequest $request)
    {
        $request->validated();
        
        if($role = RolesRepository::save($request->all()))
            return response()->json(['status' => 'success', 'data' =>$role]);

        return response()->json(['status' => 'error', 'message' => Roles::error()],400);
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function findById($id)
    {
        $role = new RolesRepository($id);
        if($role->findByID())
              return response()->json(['status' => 'success', 'data' => $role->getModel()]);

        return response()->json(['status' => 'error', 'message' => $role->error()],400);
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
        $role = new RolesRepository($id);
        if($role->deleteById())
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);

        return response()->json(['status' => 'error', 'message' => $role->error()],400);
    }
}
