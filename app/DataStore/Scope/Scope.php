<?php

namespace WA\DataStore\Scope;
use WA\DataStore\BaseDataStore;
use Log;
use DB;

/**
 * Class Scope.
 */
class Scope extends BaseDataStore
{
     
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany('WA\DataStore\Permission\Permission', 'scope_permission', 'scope_id', 'permission_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function getTransformer()
    {
        return new ScopeTransformer();
    }

    public static function getByName($name){
        return Scope::where('name', $name)->get();
    }

   /* public function getId(){
        return $this->id;
    }*/
}