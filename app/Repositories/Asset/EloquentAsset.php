<?php

namespace WA\Repositories\Asset;

use Illuminate\Database\Eloquent\Model;
use Log;
use WA\Repositories\AbstractRepository;
use WA\Repositories\Carrier\CarrierInterface;
use WA\Repositories\User\UserInterface;
use WA\Repositories\JobStatus\JobStatusInterface;
use WA\Repositories\Traits\AttributableMethods;
use WA\Services\Converter\Currency;

class EloquentAsset extends AbstractRepository implements AssetInterface
{
    use AttributableMethods;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \WA\Repositories\User\UserInterface
     */
    protected $user;

    /**
     * @var \WA\Repositories\Device\DeviceInterface
     */
    protected $device;

    /**
     * @var JobStatusInterface
     */
    protected $jobStatus;

    /**
     * @var CarrierInterface
     */
    protected $carrier;

    public function __construct(
        Model $model,
        JobStatusInterface $jobStatus,
        UserInterface $user,
        CarrierInterface $carrier
    ) {
        parent::__construct($model);
        $this->model = $model;
        $this->jobStatus = $jobStatus;
        $this->user = $user;
        $this->carrier = $carrier;
    }

    /**
     * Get the Asset by its identification.
     *
     * @param string $identifier
     *
     * @return Object object of asset information
     */
    public function byIdentification($identifier)
    {
        try {
            return $this->model->where('identification', $identifier)
                ->first();
        } catch (\PDOException $e) {
            Log::error('There was a problem: '.$e->getMessage());
        }
    }

    /**
     * Get the User attached to this asset.
     *
     * @param $user (currently grabbing by the companyId or company Identifier)
     * @param $page
     * @param $limit
     * @param $all
     *
     * @return \WA\DataStore\Asset\Asset | null
     */
    public function byUser($user, $page = 1, $limit = 10, $all = true)
    {
        $foundUser = $this->user->byCompanyId($user);

        if (!$foundUser) {
            return;
        }

        return $foundUser->assets;
    }

    /**
     * Get the Device attached to this asset.
     *
     * @param $device
     * @param $page
     * @param $limit
     * @param $all
     *
     * @return Object object of device information
     */
    public function byDevice($device, $page = 1, $limit = 10, $all = true)
    {
        $foundDevice = $this->device->byIdentification($device);

        if (!$foundDevice) {
            return;
        }

        return $foundDevice->assets;
    }

    /**
     * Get the Unassigned assets.
     *
     * @param bool $all loads all by default, or returns a random unassigned one
     *
     * @return object object of unassigned devices
     */
    public function byUnassigned($all = true)
    {
        $statusId = $this->jobStatus->idByName('Pending Review');
        $model = $this->model->where('statusId', $statusId);

        if (!$all) {
            return $model->first();
        }

        return $model->get();
    }

    /**
     * Get all the unique active identifiction on the assets.
     *
     * @param array $exclude
     *
     * @return mixed
     */
    public function getUniqueIdentification(array $exclude = [])
    {
        $model = $this->model->where('isActive', 1);

        if ((bool) count($exclude)) {
            $model->whereNotIn('identification', $exclude);
        }

        $response = $model->groupBy('identification')
            ->get(['identification'])->toArray();

        return $response;
    }

    /**
     * update an asset.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $asset = $this->model->findOrFail($data['id']);
        $user = $this->user->byId($data['employeeId']);

        $statusId = $this->jobStatus->idByName('complete');

        $asset->identification = $this->setAsValue('identification', $data, $asset);
        $asset->isActive = $this->setAsValue('isActive', $data, $asset);
//        $asset->type = $this->setAsValue('type', $data, $asset);
        $asset->statusId = $statusId;

        $user->assets()->sync([$asset->id]);

        $this->attachAttributes($data['attributes'], $asset);

        try {
            $asset->save();
        } catch (\PDOException $e) {
            \Log::error('Could not update the asset'.$e->getMessage());
        }

        return true;
    }

    /**
     * Remove assigned employee from the asset relationship.
     *
     * @param $userId
     * @param $assetId
     *
     * @return bool
     */
    public function detachByUser($userId, $assetId)
    {
        $user = $this->user->byId($userId);

        if (!$user) {
            return false;
        }

        return $user->assets()->detach($assetId);
    }

    /**
     * Get Asset by Search term.
     *
     * @param string $query
     * @param bool   $paginate
     * @param int    $perPage
     *
     * @return object of asset information
     */
    public function bySearch($query, $paginate = true, $perPage = 25)
    {
        $model = $this->model->where('identification', 'LIKE', "%$query%");

        if (!$paginate) {
            return $model->get();
        }

        return $model->paginate($perPage);
    }

    /**
     * Checks  if a value contains a set-able value [helper].
     *
     * @param $varName
     * @param array $bucket
     * @param $model
     *
     * @return mixed
     */
    private function setAsValue($varName, array $bucket, $model)
    {
        return !empty($bucket[$varName]) ? $bucket[$varName] : $model->identification;
    }

    /**
     * Set the operational currency for the data set.
     *
     * @param $value
     *
     * @return mixed
     */
    public function setCurrency($value)
    {
        $this->model->setCurrency($value);
    }

    /**
     * Get the count of all unique types in the system.
     *
     * @param string $lastUpdated
     *
     * @return int of count
     */
    public function getCount($lastUpdated = null)
    {
        $model = $this->model;
        $created_column_name = 'created_at';

        if (!is_null($lastUpdated) && Schema::table($this->model->getTable(), $created_column_name)) {
            $model->where($created_column_name, $lastUpdated);
        }

        return (int) $model->count();
    }

    /**
     * Get the maximum value of the external ID.
     *
     * @return int
     */
    public function getMaxExternalId()
    {
        $externalIdColumnName = 'externalId';

        return (int) $this->model->max($externalIdColumnName);
    }

    /**
     * Update the users attached to an asset based on changes made in EV.
     *
     * @param $old_value
     * @param $new_value
     * @param $identification
     *
     * @return bool
     */
    public function updateAssetOwner($old_value, $new_value, $identification)
    {
        $asset = $this->byIdentification($identification);
        if (!$asset) {
            return false;
        }
        $assetId = $asset['id'];

        $oldEmp = $this->user->byIdentification($old_value);
        if (!empty($oldEmp)) {
            $oldEmpId = $oldEmp['id'];
            $this->detachByUser($oldEmpId, $assetId);
        }

        $newEmp = $this->user->byIdentification($new_value);
        if (!empty($newEmp)) {
            $newEmp->assets()->sync([$asset->id]);
        }

        return true;
    }

    /**
     * Update the carrier Id based on changes made in EV.
     *
     * @param $identification
     * @param $newValue
     *
     * @return bool
     */
    public function updateCarrierId($identification, $newValue)
    {
        $asset = $this->byIdentification($identification);

        if (!$asset) {
            return false;
        }
        $carrierId = !empty($this->carrier->getIdByPresentation($newValue)) ? $this->carrier->getIdByPresentation($newValue) : null;
        if (isset($carrierId)) {
            $asset['carrierId'] = $carrierId;
            try {
                $asset->save();
            } catch (\PDOException $e) {
                \Log::error('Could not update the asset'.$e->getMessage());
            }
        }

        return true;
    }
}
