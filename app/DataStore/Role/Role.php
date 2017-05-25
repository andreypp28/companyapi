<?php

namespace WA\DataStore\Role;
use Zizaco\Entrust\Contracts\EntrustRoleInterface;
use WA\DataStore\BaseDataStore;
use Zizaco\Entrust\Traits\EntrustRoleTrait;
use Config;

/**
 * Class Role.
 */
class Role extends BaseDataStore
{
    use EntrustRoleTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    public function permissions()
    {
        return $this->belongsToMany('WA\DataStore\Permission\Permission', 'permission_role', 'role_id', 'permission_id');
    }

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('entrust.roles_table');
    }
    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new RoleTransformer();
    }
    /**
     * @return array
     */
    public function getTableColumns()
    {
        return \Cache::remember($this->getTable() . '-columns', 5, function () {
            return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        });
    }
}
