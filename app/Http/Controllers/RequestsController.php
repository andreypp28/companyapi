<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use WA\DataStore\Request\RequestTransformer;
use WA\Repositories\Request\RequestInterface;

/**
 * Request resource.
 *
 * @Resource("Request", uri="/Request")
 */
class RequestsController extends FilteredApiController
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * RequestsController constructor.
     *
     * @param RequestInterface $request
     * @param Request $httpRequest
     */
    public function __construct(RequestInterface $request, Request $httpRequest)
    {
        parent::__construct($request, $httpRequest);
        $this->request = $request;
    }

    /**
     * Update contents of a Request.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        $data = $request->all();
        $data['id'] = $id;
        $request = $this->request->update($data);

        return $this->response()->item($request, new RequestTransformer(), ['key' => 'requests']);
    }

    /**
     * Create a new Request.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $request = $this->request->create($data);

        return $this->response()->item($request, new RequestTransformer(), ['key' => 'requests']);
    }

    /**
     * Delete a Request.
     *
     * @param $id
     */
    public function delete($id)
    {
        $this->request->deleteById($id);
    }
}
