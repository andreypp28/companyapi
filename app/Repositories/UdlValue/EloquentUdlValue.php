<?php

namespace WA\Repositories\UdlValue;

use Illuminate\Database\Eloquent\Model;
use WA\Repositories\AbstractRepository;

class EloquentUdlValue extends AbstractRepository implements UdlValueInterface
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get the UDL value that matches the id.
     *
     * @param int $udlValueId
     *
     * @return object object of the udl information
     */
    public function byId($udlValueId)
    {
        $response = $this->model->where('id', $udlValueId);

        return $response->first();
    }

    /**
     * Get the UDL value that matches the name or create if it does not exist.
     *
     * @param string $name
     * @param int    $udlId
     * @param int    $companyId
     * @param int    $externalId
     *
     * @return object object of the udl information
     */
    public function byNameOrCreate($name, $udlId, $companyId, $externalId = 0)
    {
        $udlValue = $this->byName($name);

        if (!$udlValue) {
            $udlValue = $this->create(
                [
                    'name' => $name,
                    'udlId' => $udlId,
                    'companyId' => $companyId,
                    'externalId' => $externalId,
                ]
            );
        }

        return $udlValue;
    }

    /**
     * Get the UDL value that matches the name.
     *
     * @param string $name
     * @param int    $companyId
     *
     * @return object object of the udl information
     */
    public function byName($name, $companyId = null)
    {
        $response = $this->model->where('name', $name);

        if (!is_null($companyId)) {
            return  $response->where('companyId', $companyId)->first();
        }

        return $response->first();
    }

    /**
     * Creates a new UDL value.
     *
     * @param array $data
     *
     * @return bool
     */
    public function create(array $data)
    {
        return
            $this->model->firstOrCreate(
                [
                    'name' => $data['name'],
                    'udlId' => $data['udlId'],
                    'externalId' => isset($data['externalId']) ? $data['externalId'] : null,
                ]
            );
    }

    public function update(array $data)
    {
        $udlValue = $this->model->find($data['id']);

        if (!$udlValue) {
            return 'notExist';
        }

        $udlValue->name = isset($data['name']) ? $data['name'] : $udlValue->name;
        $udlValue->udlId = isset($data['udlId']) ? $data['udlId'] : $udlValue->udlId;
        $udlValue->externalId = isset($data['externalId']) ? $data['externalId'] : $udlValue->externalId;

        if (!$udlValue->save()) {
            return 'notSaved';
        }

        return $udlValue;
    }

    /**
     * Get all by UDL Value by the UDL ID.
     *
     * @param int $udlId
     *
     * @return object of udl values
     */
    public function byUdlId($udlId)
    {
        return $this->model->where('udlId', $udlId)
            ->groupBy('name')
            ->get();
    }

    /**
     * Get the User Count on the UDL Value.
     *
     * @param $name
     * @param $companyId
     *
     * @return int employee count
     */
    public function getUserCount($name, $companyId = null)
    {
    }
}
