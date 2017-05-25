<?php

namespace WA\DataStore\Permission;
use Zizaco\Entrust\Contracts\EntrustPermissionInterface;
use WA\DataStore\BaseDataStore;
use Zizaco\Entrust\Traits\EntrustPermissionTrait;
use Config;

/**
 * Class Permission.
 */
class Permission extends BaseDataStore
{
    use EntrustPermissionTrait;
    
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = Config::get('entrust.permissions_table');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function scopes()
    {
        return $this->belongsToMany('WA\DataStore\Scope\Scope', 'scope_permission', 'scope_id', 'permission_id');
    }

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new PermissionTransformer();
    }
}
