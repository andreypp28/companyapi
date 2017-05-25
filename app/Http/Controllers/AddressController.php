<?php

namespace WA\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use WA\DataStore\Address\Address;
use WA\DataStore\Address\AddressTransformer;
use WA\Repositories\Address\AddressInterface;

/**
 * Address resource.
 *
 * @Resource("address", uri="/address")
 */
class AddressController extends FilteredApiController
{
    /**
     * @var AddressInterface
     */
    protected $address;

    /**
     * Address Controller constructor.
     *
     * @param AddressInterface $address
     * @param Request $request
     */
    public function __construct(AddressInterface $address, Request $request)
    {
        parent::__construct($address, $request);
        $this->address = $address;
    }

    /**
     * Update contents of an Address.
     *
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store($id, Request $request)
    {
        if ($this->isJsonCorrect($request, 'addresses')) {
            try {
                $data = $request->all()['data'];
                $data['attributes']['id'] = $id;
                $address = $this->address->update($data['attributes']);

                if ($address == 'notExist') {
                    $error['errors']['addresses'] = Lang::get('messages.NotExistClass', ['class' => 'Address']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['notexists']);
                }

                if ($address == 'notSaved') {
                    $error['errors']['addresses'] = Lang::get('messages.NotSavedClass', ['class' => 'Address']);
                    //$error['errors']['Message'] = $e->getMessage();
                    return response()->json($error)->setStatusCode($this->status_codes['conflict']);
                }

                return $this->response()->item($address, new AddressTransformer(),
                    ['key' => 'addresses'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['addresses'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Address', 'option' => 'updated', 'include' => '']);
                //$error['errors']['addressMessage'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Create a new Address.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function create(Request $request)
    {
        if ($this->isJsonCorrect($request, 'addresses')) {
            try {
                $data = $request->all()['data']['attributes'];
                $address = $this->address->create($data);

                return $this->response()->item($address, new AddressTransformer(),
                    ['key' => 'addresses'])->setStatusCode($this->status_codes['created']);
            } catch (\Exception $e) {
                $error['errors']['addresses'] = Lang::get('messages.NotOptionIncludeClass',
                    ['class' => 'Address', 'option' => 'created', 'include' => '']);
                //$error['errors']['Message'] = $e->getMessage();
            }
        } else {
            $error['errors']['json'] = Lang::get('messages.InvalidJson');
        }

        return response()->json($error)->setStatusCode($this->status_codes['conflict']);
    }

    /**
     * Delete a Address.
     *
     * @param $id
     */
    public function delete($id)
    {
        $address = Address::find($id);
        if ($address != null) {
            $this->address->deleteById($id);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotExistClass', ['class' => 'Address']);
            return response()->json($error)->setStatusCode($this->status_codes['notexists']);
        }

        $address = Address::find($id);
        if ($address == null) {
            return array("success" => true);
        } else {
            $error['errors']['delete'] = Lang::get('messages.NotDeletedClass', ['class' => 'Address']);
            return response()->json($error)->setStatusCode($this->status_codes['conflict']);
        }
    }
}
