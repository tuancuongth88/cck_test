<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laratrust\Traits\LaratrustUserTrait;

/**
 * @OA\Schema(
 *     schema="UserType",
 *     title="UserType",
 *     description="Role User:
 *     - `1`: supper admin
 *     - `2`: company manager
 *     - `3`: hr manager
 *     - `4`: company
 *     - `5`: hr",
 *     type="int",
 *     enum={1,2,3,4,5},
 * )
 * @OA\Schema(
 *     schema="UserStatus",
 *     title="UserStatus",
 *     description="StatusUser:
 *     - `1`: Pending
 *     - `2`: Approve
 *     - `3`: Reject
 *     - `4`: Stop",
 *     type="int",
 *     enum={1,2,3,4},
 * )
 **/

class User extends Authenticatable implements JWTSubject
{
    use LaratrustUserTrait;
    use HasFactory;
    use Notifiable;
    use AuthenticableTrait;
    use SoftDeletes;

    protected $table = 'users';
    public $timestamps = true;
    protected $dates = ['deleted_at'];

    const MAIL_ADDRESS = 'mail_address';
    const LOGIN_ID = 'login_id';
    const PASSWORD = 'password';
    const TYPE = 'type';
    const STATUS = 'status';

    protected $fillable = [
        self::MAIL_ADDRESS,
        self::LOGIN_ID,
        self::PASSWORD,
        self::TYPE,
        self::STATUS
    ];



    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function routeNotificationForMail()
    {
        return $this->mail_address;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'guard' => 'user'
        ];
    }

    public function setPasswordAttribute($value)
    {
        if($value){
            $this->attributes['password'] = Hash::make($value);
        }
    }

   public function role(){
      return $this->morphToMany(Role::class, 'user', 'role_user');
   }

    public function company(){
        return $this->hasOne(Company::class, 'user_id', 'id');
    }

    public function hrOrganization(){
        return $this->hasOne(HrOrganization::class, 'user_id', 'id');
    }

    public static function listStatus(){
        return [
            EXAMINATION_PENDING => '審査待ち ',
            CONFIRM => '承認 ',
            REJECT => '却下 ',
            STOP_USING => '利用停止 ',
        ];
    }

    public static function getPermissionName($user)
    {
        if(in_array($user->type, [SUPER_ADMIN, COMPANY_MANAGER, HR_MANAGER])){
            return PERMISSION_NAME[$user->type];
        }
        if($user->type == COMPANY){
            return @$user->company->company_name;
        }
        if ($user->type == HR){
            return @$user->hrOrganization->corporate_name_ja;
        }
    }
}
