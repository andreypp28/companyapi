<?php

namespace WA\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Category\CategoryApp;
use WA\DataStore\Category\CategoryAppTransformer;
use WA\Repositories\Category\CategoryAppsInterface;

/**
 * CategoryApps resource.
 *
 * @Resource("categoryapps", uri="/categoryapps")
 */
class CategoryAppsController extends FilteredApiController
{
    /**
     * @var CategoryAppsInterface
     */
    protected $categoryApps;

    /**
     * CategoryAppsController constructor.
     *
     * @param CategoryAppsInterface $categoryApps
     * @param Request $request
     */
    public function __construct(CategoryAppsInterface $categoryApps, Request $request)
    {
        parent::__construct($categoryApps, $request);
        $this->categoryApps = $categoryApps;
    }

    /**
     * Update contents of a CategoryApps.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'categoryapps')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');

            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        try {
            $data = $request->all()['data'];
            $data['attributes']['id'] = $id;
            $categoryApps = $this->categoryApps->update($data['attributes']);

            if ($categoryApps == 'notExist') {
                DB::rollBack();
                $error['errors']['categoryApps'] = Lang::get('messages.NotExistClass', ['class' => 'CategoryApps']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['notexists']);
            }

            if ($categoryApps == 'notSaved') {
                DB::rollBack();
                $error['errors']['categoryApps'] = Lang::get('messages.NotSavedClass', ['class' => 'CategoryApps']);
                //$error['errors']['Message'] = $e->getMessage();
                return response()->json($error)->setStatusCode($this->status_codes['conflict']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['categoryapps'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'CategoryApps', 'option' => 'updated', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        if (isset($data['relationships'])) {
            if (isset($data['relationships']['images'])) {
                if (isset($data['relationships']['images']['data'])) {
                    try {
                        $dataImages = $this->parseJsonToArray($data['relationships']['images']['data'], 'images');
                        $categoryApps->images()->sync($dataImages);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $error['errors']['images'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'CategoryApps', 'option' => 'created', 'include' => 'Images']);
                        //$error['errors']['Message'] = $e->getMessage();
                        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                    }
                }
            }

            if (isset($data['relationships']['Apps'])) {
                if (isset($data['relationships']['Apps']['data'])) {
                    try {
                        $dataApps = $this->parseJsonToArray($data['relationships']['apps']['data'], 'apps');
                        $categoryApps->apps()->sync($dataApps);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $error['errors']['Apps'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'CategoryApps', 'option' => 'created', 'include' => 'Apps']);
                        //$error['errors']['Message'] = $e->getMessage();
                        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                    }
                }
            }
        }

        DB::commit();

        return $this->response()->item($categoryApps, new CategoryAppTransformer(),
            ['key' => 'categoryapps'])->setStatusCode($this->status_codes['created']);
    }

    /**
     * Create a new CategoryApps.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        /*
         * Checks if Json has data, data-type & data-attributes.
         */
        if (!$this->isJsonCorrect($request, 'categoryapps')) {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');

            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        DB::beginTransaction();

        try {
            $data = $request->all()['data'];
            $categoryApps = $this->categoryApps->create($data['attributes']);
        } catch (\Exception $e) {
            DB::rollBack();
            $error['errors']['categoryapps'] = Lang::get('messages.NotOptionIncludeClass',
                ['class' => 'CategoryApps', 'option' => 'created', 'include' => '']);
            //$error['errors']['Message'] = $e->getMessage();
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }

        if (isset($data['relationships'])) {
            if (isset($data['relationships']['images'])) {
                if (isset($data['relationships']['images']['data'])) {
                    try {
                        $dataImages = $this->parseJsonToArray($data['relationships']['images']['data'], 'images');
                        $categoryApps->images()->sync($dataImages);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $error['errors']['images'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'CategoryApps', 'option' => 'created', 'include' => 'Images']);
                        //$error['errors']['Message'] = $e->getMessage();
                        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                    }
                }
            }

            if (isset($data['relationships']['Apps'])) {
                if (isset($data['relationships']['Apps']['data'])) {
                    try {
                        $dataApps = $this->parseJsonToArray($data['relationships']['apps']['data'], 'apps');
                        $categoryApps->Apps()->sync($dataApps);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $error['errors']['apps'] = Lang::get('messages.NotOptionIncludeClass',
                            ['class' => 'CategoryApps', 'option' => 'created', 'include' => 'Apps']);
                        //$error['errors']['Message'] = $e->getMessage();
                        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                    }
                }
            }
        }

        DB::commit();

        return $this->response()->item($categoryApps, new CategoryAppTransformer(),
            ['key' => 'categoryapps'])->setStatusCode($this->status_codes['created']);
    }

    /**
     * Delete a CategoryApps.
     *
     * @param $id
     */
    public function delete($id)
    {
        $categoryApps = CategoryApp::find($id);
        if ($categoryApps <> null) {
            $this->categoryApps->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'CategoryApps']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $categoryApps = CategoryApp::find($id);
        if ($categoryApps == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'CategoryApps']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
