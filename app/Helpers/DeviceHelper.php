<?php

namespace WA\Helpers;

use App;
use DB;
use Exception;
use Illuminate\Http\Request;
use Input;
use Redirect;
use View;
use WA\Helpers\DBSnapshot;
use WA\Helpers\Traits\SetLimits;
use WA\Http\Controllers\Auth\AuthorizedController;
use WA\Repositories\FeatureRatePlanRepositoryInterface;
/**
 * Class UserHelper.
 */
class DeviceHelper extends AuthorizedController
{
    /**
     * @param null $deviceTypeId
     *
     * @return mixed|null|string
     *
     * @throws Exception
     */
    public function generateIds($deviceTypeId = null)
    {
        $devicetype = \WA\DataStore\DeviceType\DeviceType::whereId($deviceTypeId)->first();
        
        if (!empty($deviceTypeId)) {
            $id = null;
            if (empty($devicetype)) {
                $id = $this->randGenerator('NAN');
            } else {
                $id = $this->randGenerator($devicetype->name);
            }

            $device = app()->make('\WA\Repositories\Device\DeviceInterface');
            $dup_id = $device->byIdentification($id);

            if (count($dup_id)) {
                $this->generateIds($deviceTypeId);
            }

            return $id;
        }

        return false;
    }
    /**
     * @param $salt
     *
     * @return mixed|string
     */
    public function randGenerator($salt, $length = 10, $seperator = '-')
    {
        $rand_id = crypt(uniqid(rand(), 100 ^ 2), $salt);
        $rand_id = strip_tags(stripslashes($rand_id));
        $rand_id = str_replace('.', '', $rand_id);
        $rand_id = strrev(str_replace('/', '', $rand_id));
        $rand_id = strtoupper(substr($rand_id, 0, $length));
        $rand_id = $salt . $seperator . $rand_id;
        return $rand_id;
    }
}