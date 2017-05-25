<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Role\Role;
use WA\DataStore\Role\RoleTransformer;
use WA\Repositories\Role\RoleInterface;

/**
 * App resource.
 *
 * @Resource("role", uri="/roles")
 */
class RolesController extends FilteredApiController
{
    /**
     * @var roleInterface
     */
    protected $role;

    /**
     * rolesController constructor.
     *
     * @param roleInterface $role
     * @param Request $request
     */
    public function __construct(RoleInterface $role, Request $request)
    {
        parent::__construct($role, $request);
        $this->role = $role;
    }

    /**
     * Update contents of a role.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'roles')) {
            try {
                $data = $request->all()['data'];
                $data['attributes']['id'] = $id;
                $role = $this->role->update($data['attributes']);

                if ($role == 'notExist') {
                    $error['errors']['role'] = Lang::get('messages.NotExistClass', ['class' => 'Role']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($role == 'notSaved') {
                    $error['errors']['role'] = Lang::get('messages.NotSavedClass', ['class' => 'Role']);
                    //$error['errors']['devicesMessage'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($role, new roleTransformer(),
                    ['key' => 'roles'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['roles'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Role', 'option' => 'updated', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new role.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'roles')) {
            try {
                $data = $request->all()['data']['attributes'];
                $role = $this->role->create($data);

                return $this->response()->item($role, new roleTransformer(),
                    ['key' => 'roles'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['roles'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Role', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete an role.
     *
     * @param $id
     */
    public function delete($id)
    {
        $role = Role::find($id);
        if ($role != null) {
            $this->role->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Role']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $role = Role::find($id);
        if ($role == null) {
            return array('success' => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Role']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}