<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Scope\Scope;
use WA\DataStore\Scope\ScopeTransformer;
use WA\Repositories\Scope\ScopeInterface;

/**
 * App resource.
 *
 * @Resource("Scope", uri="/Scopes")
 */
class ScopesController extends FilteredApiController
{
    /**
     * @var ScopeInterface
     */
    protected $scope;

    /**
     * ScopesController constructor.
     *
     * @param ScopeInterface $Scope
     * @param Request $request
     */
    public function __construct(ScopeInterface $scope, Request $request)
    {
        parent::__construct($scope, $request);
        $this->scope = $scope;
    }

    /**
     * Update contents of a Scope.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'scopes')) {
            try {
                $data = $request->all()['data']['attributes'];
                $data['id'] = $id;
                $scope = $this->scope->update($data);

                if ($scope == 'notExist') {
                    $error['errors']['scope'] = Lang::get('messages.NotExistClass', ['class' => 'Scope']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($scope == 'notSaved') {
                    $error['errors']['scope'] = Lang::get('messages.NotSavedClass', ['class' => 'Scope']);
                    //$error['errors']['devicesMessage'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($scope, new ScopeTransformer(),
                    ['key' => 'scopes'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['scopes'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Scope', 'option' => 'updated', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new Scope.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'scopes')) {
            try {
                $data = $request->all()['data']['attributes'];
                $scope = $this->scope->create($data);

                return $this->response()->item($scope, new ScopeTransformer(),
                    ['key' => 'scopes'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['scopes'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Scope', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete an Scope.
     *
     * @param $id
     */
    public function delete($id)
    {
        $scope = Scope::find($id);
        if ($scope != null) {
            $this->scope->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Scope']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $scope = Scope::find($id);
        if ($scope == null) {
            return array('success' => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Scope']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}