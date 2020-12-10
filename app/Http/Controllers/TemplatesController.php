<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use App\Repositories\TemplatesRepository;
use App\Http\Requests\TemplatesRequest;
use App\Repositories\PermissionRepository;

class TemplatesController extends Controller
{
    public function index()
    {
        return response()->json(array('data'=>TemplatesRepository::getTemplates(), 'permission'=> PermissionRepository::getStatusPermission()));
    }

    /**
     * data save to storage
     *
     * @param PermissionRequest $request
     * @return mixed
     */
    public function save(TemplatesRequest $request)
    {
        $request->validated();
        $template = TemplatesRepository::save($request->all());
        if(!$template)
            return response()->json(['status' => 'error', 'message' => TemplatesRepository::error()]);

         return response()->json(['status' => 'success', 'data' => $template]);
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function findById($id)
    {
        $template = new TemplatesRepository($id);
        if(!$template->findByID())
             return response()->json(['status' => 'error', 'message' => $template->error()]);

         return response()->json(['status' => 'success', 'data' => $template->getModel()]);
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
        $templates = new TemplatesRepository($id);
        if($templates->deleteById())
            return response()->json(['status' => 'success', 'message' => 'Data has been deleted']);

        return response()->json(['status' => 'error', 'message' => $templates->error()],400);
    }
}
