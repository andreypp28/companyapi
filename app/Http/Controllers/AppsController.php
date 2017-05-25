<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\App\App;
use WA\DataStore\App\AppTransformer;
use WA\Repositories\App\AppInterface;

/**
 * App resource.
 *
 * @Resource("app", uri="/apps")
 */
class AppsController extends FilteredApiController
{
    /**
     * @var AppInterface
     */
    protected $app;

    /**
     * AppsController constructor.
     *
     * @param AppInterface $app
     * @param Request $request
     */
    public function __construct(AppInterface $app, Request $request)
    {
        parent::__construct($app, $request);
        $this->app = $app;
    }

    /**
     * Update contents of a app.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'apps')) {
            try {
                $data = $request->all()['data'];
                $data['attributes']['id'] = $id;
                $app = $this->app->update($data['attributes']);

                if ($app == 'notExist') {
                    $error['errors']['app'] = Lang::get('messages.NotExistClass', ['class' => 'App']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($app == 'notSaved') {
                    $error['errors']['app'] = Lang::get('messages.NotSavedClass', ['class' => 'App']);
                    //$error['errors']['devicesMessage'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($app, new AppTransformer(),
                    ['key' => 'apps'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['apps'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'App', 'option' => 'updated', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new App.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'apps')) {
            try {
                $data = $request->all()['data']['attributes'];
                $app = $this->app->create($data);

                return $this->response()->item($app, new AppTransformer(),
                    ['key' => 'apps'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['apps'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'App', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete an App.
     *
     * @param $id
     */
    public function delete($id)
    {
        $app = App::find($id);
        if ($app != null) {
            $this->app->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'App']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $app = App::find($id);
        if ($app == null) {
            return array('success' => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'App']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
