<?php

namespace WA\DataStore\User;

//namespace App;
use Cache;
use Illuminate\Auth\Authenticatable as IllumnateAuthenticableTrait;
use Illuminate\Auth\Passwords\CanResetPassword as IlluminateCanResetPasswordTrait;
use Illuminate\Contracts\Auth\Authenticatable as IllumincateAuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as IlluminateCanResetPasswordContract;
use Illuminate\Http\Request;
use Laravel\Passport\HasApiTokens;
use WA\DataStore\BaseDataStore;
use League\OAuth2\Server\Exception\OAuthServerException;

/**
 * Class User.
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\UdlValue\UdlValue[] $udlValues
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Asset\Asset[] $assets
 * @property-read \WA\DataStore\Company\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Device\Device[] $devices
 * @property-read \WA\DataStore\UdlValuePath\UdlValuePath $department
 * @property-read \WA\DataStore\Location\Location $location
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Role\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Allocation\Allocation[] $allocations
 * @property-read \Illuminate\Database\Eloquent\Collection|\WA\DataStore\Content\Content[] $contents
 * @mixin \Eloquent
 */
class User extends BaseDataStore implements IlluminateCanResetPasswordContract, IllumincateAuthenticatableContract
{
    //    use SoftDeletes;
//    use RevisionableTrait;
//    use EntrustUserTrait {
//        EntrustUserTrait::boot insteadof RevisionableTrait;
//    }
    use IlluminateCanResetPasswordTrait, IllumnateAuthenticableTrait;
    use HasApiTokens;
    use \Zizaco\Entrust\Traits\EntrustUserTrait;
    public $timestamps = true;
    protected $tableName = 'users';
    protected $dontKeepRevisionOf = [
        'lft',
        'rgt',
        'syncId',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'identification',
        'email',
        'alternateEmail',
        'password',
        'username',
        'confirmation_code',
        'remember_token',
        'confirmed',
        'firstName',
        'lastName',
        'alternateFirstName',
        'supervisorEmail',
        'companyUserIdentifier',
        'isSupervisor',
        'isValidator',
        'isActive',
        'rgt',
        'lft',
        'hierarchy',
        'defaultLang',
        'notes',
        'level',
        'notify',
        'companyId',
        'syncId',
        'supervisorId',
        'externalId',
        'approverId',
        'defaultLocationId',
        'deleted_at',
        'created_at',
        'updated_at'

    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'uuid',
        'password',
        'confirmation_code',
        'remember_token',
        'confirmed',
        'isActive',
        'externalId'
    ];

    protected $revisionFormattedFields = [
        'isActive'   => 'boolean:No|Yes',
        'deleted_at' => 'isEmpty:Active|Deleted',
    ];

    protected $revisionFormattedFieldName = array(
        'firstName'       => 'First Name',
        'lastName'        => 'Last Name',
        'supervisorEmail' => 'Supervisor Email',
        'deleted_at'      => 'Deleted At',
    );

    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    protected $parentColumn = 'supervisorId';
    protected $depthColumn = 'hierarchy';
    protected $leftColumn = 'lft';
    protected $rightColumn = 'rgt';
    protected $guarded = ['supervisorId', 'hierarchy', 'lft', 'rgt'];

    protected $dates = ['deleted_at'];

    protected $morphClass = 'user';

    /**
     * Get the transformer instance.
     *
     * @return mixed
     */
    public function findForPassport($email)
    {
        return User::where('email', $email)->first();
    }

    public function findForIdentification($identification)
    {
        return User::where('identification', $identification)->first();
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getTransformer()
    {
        return new UserTransformer();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return substr($this->email, 0, strpos($this->email, '@'));
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function udlValues()
    {
        return $this->belongsToMany('WA\DataStore\UdlValue\UdlValue', 'user_udls', 'userId', 'udlValueId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function assets()
    {
        return $this->hasMany('WA\DataStore\Asset\Asset', 'userId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companies()
    {
        return $this->belongsTo('WA\DataStore\Company\Company', 'companyId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('WA\DataStore\UdlValuePath\UdlValuePath', 'departmentId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo('WA\DataStore\Location\Location', 'defaultLocationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('WA\DataStore\Role\Role', 'role_user', 'user_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function devicevariations()
    {
        return $this->belongsToMany('WA\DataStore\DeviceVariation\DeviceVariation', 'user_device_variations', 'userId', 'deviceVariationId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany('WA\DataStore\Service\Service', 'user_services', 'userId', 'serviceId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allocations()
    {
        return $this->hasMany('WA\DataStore\Allocation\Allocation', 'userId');
    }

    /**
     * Get all the employee related static contents.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function contents()
    {
        return $this->hasMany('WA\DataStore\Content\Content', 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function addresses()
    {
        return $this->belongsToMany('WA\DataStore\Address\Address', 'user_address', 'userId', 'addressId');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('WA\DataStore\Order\Order', 'userId');
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function hasDevice($id)
    {
        return !$this->devices->filter(
            function ($device) use ($id) {
                return $device->id == $id;
            }
        )->isEmpty();
    }

    /**
     * Verify and retrieve user by custom token request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function byPassportSSOGrantRequest(Request $request)
    {
        try {
            if ($request->input('grant_type') == 'sso') {
                return $this->SSOGrantVerify($request);
            }
        } catch (\Exception $e) {
            throw OAuthServerException::accessDenied($e->getMessage());
        }
        return null;
    }

    public function SSOGrantVerify(Request $request)
    {
        $uuid = $request->input('uuid');
        $laravelUser = Cache::get('saml2user_' . $uuid['uuid']);
        if (!isset($laravelUser)) {
            return null;
        } else {
            return $laravelUser;
        }
    }

    public function getCurrentBillMonths()
    {
        return $this->company->companyCurrentBillMonths;
    }
}
