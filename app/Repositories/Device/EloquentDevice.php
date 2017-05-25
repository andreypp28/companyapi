<?php

namespace WA\Repositories\Device;

use Illuminate\Database\Eloquent\Model;
use Schema;
use WA\Repositories\AbstractRepository;
use WA\Repositories\JobStatus\JobStatusInterface as StatusInterface;
use WA\Repositories\Traits\AttributableMethods;

class EloquentDevice extends AbstractRepository implements DeviceInterface
{
    use AttributableMethods;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var StatusInterface
     */
    protected $status;

    /**
     * @param Model           $model
     * @param StatusInterface $status
     */
    public function __construct(Model $model, StatusInterface $status)
    {
        parent::__construct($model);
        $this->model = $model;
        $this->status = $status;
    }

    /**
     * Get the device information by their employee.
     *
     * @param $userId (using CompanyID for now)
     *
     * @return object object of device information by the employee
     */
    public function byUser($userId)
    {
        $userDevices =
            $this->model->with('users', function ($q) use ($userId) {
                $q->where('employeeId', $userId);
            })->get();

        if (!$userDevices) {
            return;
        }

        return $userDevices;
    }
    /**
     * Create a new Device.
     *
     * @param array $data
     *
     * @return Device | false
     */
    public function create(array $data){
        
        if(isset($data['name']) && $data['name'] !== ''){
            $deviceData['name'] = $data['name'];
        } else {
            $deviceData['name'] = null;
        }
        
        if(isset($data['properties']) && $data['properties'] !== ''){
            $deviceData['properties'] = $data['properties'];
        } else {
            $deviceData['properties'] = null;
        }
        
        if(isset($data['deviceTypeId']) && $data['deviceTypeId'] !== ''){
            $deviceData['deviceTypeId'] = $data['deviceTypeId'];
        }
        
        if(isset($data['statusId']) && $data['statusId'] !== ''){
            $deviceData['statusId'] = $data['statusId'];
        } else {
            $deviceData['statusId'] = null;
        }
        
        if(isset($data['externalId']) && $data['externalId'] !== ''){
            $deviceData['externalId'] = $data['externalId'];
        } else {
            $deviceData['externalId'] = null;
        }
        
        if(isset($data['syncId']) && $data['syncId'] !== ''){
            $deviceData['syncId'] = $data['syncId'];
        } else {
            $deviceData['syncId'] = null;
        }
        
        if(isset($data['make']) && $data['make'] !== ''){
            $deviceData['make'] = $data['make'];
        } else {
            $deviceData['make'] = null;
        }
        
        if(isset($data['model']) && $data['model'] !== ''){
            $deviceData['model'] = $data['model'];
        } else {
            $deviceData['model'] = null;
        }

        if(isset($data['defaultPrice']) && $data['defaultPrice'] !== ''){
            $deviceData['defaultPrice'] = $data['defaultPrice'];
        } else {
            $deviceData['defaultPrice'] = null;
        }
        
        if(isset($data['currency']) && $data['currency'] !== ''){
            $deviceData['currency'] = $data['currency'];
        } else {
            $deviceData['currency'] = null;
        }

        if(isset($data['identification']) && $data['identification'] !== ''){
            $deviceData['identification'] = $data['identification'];
        } else {
            $generator = app()->make('WA\Helpers\DeviceHelper');
            $ident = $generator->generateIds($data['deviceTypeId']);
            if (!$ident) {
                return false;
            }
            $deviceData['identification'] = $ident;
        } 
        $device = $this->model->create($deviceData);

        if (!$device) {
            return false;
        }

        return $device;
        
    }

    /**
     * Get device by their identification.
     *
     * @param $identification
     *
     * @return object object of device information
     */
    public function byIdentification($identification)
    {
        return $this->model
            ->where('identification', $identification)
            ->first();
    }

    /**
     * Get the devices that are pending review ( that have not yet being assigned users).
     *
     * @param bool $all
     *
     * @return object object of device information, for unassigned
     */
    public function byUnassigned($all = true)
    {
        $statusId = $this->status->idByName('Pending Review');

        $model = $this->model->where('statusId', $statusId);

        if (!$all) {
            return $model->first();
        }

        return $model->get();
    }

    /**
     * Sync an asset to devices.
     *
     * @param int   $id  of the device
     * @param array $ids of the assets to sync device with
     *
     * @return bool
     */
    public function syncAsset($id, array $ids)
    {
        $device = $this->byId($id);

        if (!$device) {
            return false;
        }

        try {
            $device->assets()->sync($ids);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sync an asset to devices.
     *
     * @param int   $id  of the device
     * @param array $ids of the assets to sync device with
     *
     * @return bool
     */
    public function syncCompanies($id, array $ids)
    {
        $device = $this->byId($id);

        if (!$device) {
            return false;
        }

        try {
            $device->companies()->sync($ids);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sync an asset to devices.
     *
     * @param int   $id  of the device
     * @param array $ids of the assets to sync device with
     *
     * @return bool
     */
    public function syncModifications($id, array $ids)
    {
        $device = $this->byId($id);

        if (!$device) {
            return false;
        }

        try {
            $device->modifications()->sync($ids);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sync an asset to devices.
     *
     * @param int   $id  of the device
     * @param array $ids of the assets to sync device with
     *
     * @return bool
     */
    public function syncCarriers($id, array $ids)
    {
        $device = $this->byId($id);

        if (!$device) {
            return false;
        }

        try {
            $device->carriers()->sync($ids);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all the unique identifications.
     *
     * @param array $exclude
     *
     * @return array of identification
     */
    public function getUniqueIdentification(array $exclude = [])
    {
        $model = $this->model;

        if ((bool) count($exclude)) {
            $model->whereNotIn('identification', $exclude);
        }

        $response = $model->groupBy('identification')
            ->get(['identification'])->toArray();

        return $response;
    }

    /**
     * Get companies devices.
     *
     * @param $id
     *
     * @return Builder/Query
     */
    public function byCompany($id)
    {
        return $this->model->whereHas('companies', function ($q) use ($id) {
            $q->where('companyId', $id);
        });
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

    public function getDataTable()
    {
        $query = $this->model->join('device_types', 'devices.deviceTypeId', '=', 'device_types.id');

        return $query;
    }

    /**
     * Update Device.
     *
     * @param array $data
     *
     * @return bool
     */
    public function update(array $data)
    {
        $device = $this->model->find($data['id']);

        if (!$device) {
            return 'notExist';
        }

        $device->name = isset($data['name']) ? $data['name'] : $device->name;
        $device->properties = isset($data['properties']) ? $data['properties'] : $device->properties;
        $device->deviceTypeId = isset($data['deviceTypeId']) ? $data['deviceTypeId'] : $device->deviceTypeId;
        $device->statusId = isset($data['statusId']) ? $data['statusId'] : $device->statusId;
        $device->externalId = isset($data['externalId']) ? $data['externalId'] : $device->externalId;
        //$device->identification = isset($data['identification']) ? $data['identification'] : $device->identification;
        $device->syncId = isset($data['syncId']) ? $data['syncId'] : $device->syncId;
        $device->make = isset($data['make']) ? $data['make'] : $device->make;
        $device->model = isset($data['model']) ? $data['model'] : $device->model;
        $device->defaultPrice = isset($data['defaultPrice']) ? $data['defaultPrice'] : $device->defaultPrice;
        $device->currency = isset($data['currency']) ? $data['currency'] : $device->currency;


        if (!$device->save()) {
            return 'notSaved';
        }

        return $device;
    }
}
