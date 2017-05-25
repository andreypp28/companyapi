<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Permission\Permission;
use WA\DataStore\Permission\PermissionTransformer;
use WA\Repositories\Permission\PermissionInterface;

/**
 * App resource.
 *
 * @Resource("Permission", uri="/Permissions")
 */
class PermissionsController extends FilteredApiController
{
    /**
     * @var permissionInterface
     */
    protected $permission;

    /**
     * permissionsController constructor.
     *
     * @param permissionInterface $permission
     * @param Request $request
     */
    public function __construct(PermissionInterface $permission, Request $request)
    {
        parent::__construct($permission, $request);
        $this->permission = $permission;
    }

    /**
     * Update contents of a permission.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'permissions')) {
            try {
                $data = $request->all()['data']['attributes'];
                $data['id'] = $id;
                $permission = $this->permission->update($data);

                if ($permission == 'notExist') {
                    $error['errors']['permission'] = Lang::get('messages.NotExistClass', ['class' => 'Permission']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($permission == 'notSaved') {
                    $error['errors']['permission'] = Lang::get('messages.NotSavedClass', ['class' => 'Permission']);
                    //$error['errors']['devicesMessage'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($permission, new permissionTransformer(),
                    ['key' => 'permissions'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['permissions'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Permission', 'option' => 'updated', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new permission.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'permissions')) {
            try {
                $data = $request->all()['data']['attributes'];
                $permission = $this->permission->create($data);

                return $this->response()->item($permission, new permissionTransformer(),
                    ['key' => 'permissions'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['permissions'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Permission', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete an permission.
     *
     * @param $id
     */
    public function delete($id)
    {
        $permission = Permission::find($id);
        if ($permission != null) {
            $this->permission->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Permission']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $permission = Permission::find($id);
        if ($permission == null) {
            return array('success' => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Permission']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}