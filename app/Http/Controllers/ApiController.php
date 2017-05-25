<?php

namespace WA\Http\Controllers;

use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Log;

/**
 * Extensible API controller.
 *
 * Class ApiController.
 */
abstract class ApiController extends BaseController
{
    use Helpers;

    /**
     * @var status_codes
     */
    public $status_codes = [
        'ok'           => 200,            //
        'created'      => 201,       // Object created and return that object.
        'accepted'     => 202,      //
        'createdCI'    => 204,     //
        'badrequest'   => 400,    // Bad Request
        'unauthorized' => 401,  // Unauthorized
        'forbidden'    => 403,     // Unsupported Request (Permissions).
        'notexists'    => 404,     // Get Put or Delete Not Exists Objects.
        'conflict'     => 409,       // Other Conflicts.
    ];


    public function applyMeta(Response $response)
    {
        return $response;
    }

    /*
     *      Checks if a JSON param has "data", "type" and "attributes" keys and "type" is equal to $type.
     *
     *      @param:
     *          "data" : {
     *              "type" : $type,
     *              "attributes" : {
     *              ...
     *      @return:
     *          boolean;
     */
    public function isJsonCorrect($request, $type)
    {
        if (!isset($request['data'])) {
            return false;
        } else {
            $data = $request['data'];
            if (!isset($data['type'])) {
                return false;
            } else {
                if ($data['type'] <> $type) {
                    return false;
                }
            }
            if (!isset($data['attributes'])) {
                return false;
            }
        }

        return true;
    }

    /*
     *      Transforms an Object to an Array for Sync purposes.
     *
     *      @param:
     *          { "type": "example", "id" : 1 },
     *          { "type": "example", "id" : 2 }
     *      @return
     *          array( 1, 2 );
     */
    public function parseJsonToArray($data, $value)
    {
        $array = array();

        foreach ($data as $info) {
            if (isset($info['type'])) {
                if ($info['type'] == $value) {
                    if (isset($info['id'])) {
                        array_push($array, $info['id']);
                    }
                }
            }
        }

        return $array;
    }

    /*
     *      Gets all the includes and verifies if they are in the includesAvailable variable.
     *
     *      @url: includesAreCorrect.
                        clean.api/devices?include=assets,assets.users,assets.users.assets,assets.users.devices,assets.users.devices.assets,assets.users.devices.carriers,assets.users.devices.companies,assets.users.devices.modifications,assets.users.devices.images,assets.users.devices.prices,assets,assets.users,assets.users.contents,assets.users.allocations,assets.users.roles,assets.devices,assets.devices.assets,assets.devices.carriers,assets.devices.carriers,assets.devices.companies,assets.devices.modifications,assets.devices.images,assets.devices.prices,assets.carriers,assets.carriers.images,assets.companies,carriers,carriers.images,companies,modifications,images,prices,assets,assets.devices,assets.devices.assets,assets.devices.carriers,assets.devices.carriers,assets.devices.companies,assets.devices.modifications,assets.devices.images,assets.devices.prices,assets.carriers,assets.carriers.images,assets.companies,carriers,carriers.images,companies,modifications,images,prices,assets,assets.carriers,assets.carriers.images,assets.companies,carriers,carriers.images,companies,modifications,images,prices
     *
     *      @return: true o false.
     */
    protected function includesAreCorrect($req, $class)
    {

        // Look at if the include parameter exists
        if ($req->has('include')) {
            // Explode the includes.
            $includes = explode(',', $req->input('include'));
        } else {
            return true;
        }

        $exists = true;
        foreach ($includes as $include) {
            $exists = $exists && $this->includesAreCorrectInf($include, $class);

            if (!$exists) {
                break;
            }
        }

        return $exists;
    }

    private function includesAreCorrectInf($include, $class)
    {
        $includesAvailable = $class->getAvailableIncludes();

        $exists = false;
        $includesAux = explode('.', $include);

        if (count($includesAux) == 1) {
            foreach ($includesAvailable as $aic) {
                if ($aic == $includesAux[0]) {
                    $exists = true;
                }
            }

            if (!$exists) {
                return false;
            } else {
                return true;
            }
        } else {
            $includes = substr($include, strlen($includesAux[0]) + 1);

            $transformer = $this->createTransformer($includesAux[0]);
            $newTransformer = new $transformer();

            return $this->includesAreCorrectInf($includes, $newTransformer);
        }
    }

    private function createTransformer($var) 
    {
        if($var === 'devicevariations') {
            return "\\WA\\DataStore\\DeviceVariation\\DeviceVariationTransformer";
        }

        if($var === 'devicetypes') {
            return "\\WA\\DataStore\\DeviceType\\DeviceTypeTransformer";
        }

        if($var === 'udlvalues') {
            return "\\WA\\DataStore\\UdlValue\\UdlValueTransformer";
        }

        $model = title_case(str_singular($var));
        return "\\WA\\DataStore\\${model}\\${model}Transformer";
    }

    /**
     *  @param: $data = Data retrieved from Request. (the new information we will work)
     *  @param: $databaseinformation = Information retrieved from database. (the information we will modify)
     *  @param: $interface = Interface needed to delete the includes.
     *  @param: $type = The name of the include.
     */
    public function deleteNotRequested($data, $databaseinformation, $interface, $type) {
        $arrayFromData = array();
        foreach ($data as $any) {
            if(isset($any['type']) && $any['type'] == $type) {
                array_push($arrayFromData, $any['id']);
            } else {
                throw new \Exception('Invalid Json');
            }
        }
        
        $arrayFromDB = array();
        foreach ($databaseinformation as $some) {
            array_push($arrayFromDB, $some->id);
        }

        $canBeDeleted = array_diff($arrayFromDB, $arrayFromData);

        foreach ($canBeDeleted as $some) {
            $interface->deleteById($some);
        }
    }

    /**
     *  @param: $data = Information.
     *  @param: $email = Email to Send.
     *  @param: $type = Model Created.
     */
    public function sendConfirmationEmail($userId, $type) {

        try {
            $res = \Illuminate\Support\Facades\Mail::send(
                'emails.notifications.new_order_received', // VIEW NAME
                [
                    'userId' => $userId,
                    'type' => $type
                ], // PARAMETERS PASSED TO THE VIEW
                function ($message) use ($type) {
                    $message->subject('New '.$type.' Received');
                    $message->from(env('MAIL_FROM_ADDRESS'));
                    $message->to(env('MAIL_USERNAME_TO'));
                    // @TODO: Send Mail To The Logged User who Made The Order.
                } // CALLBACK
            );

        } catch (\Exception $e) {
            return false;
        }
        return true;        
    }
}
