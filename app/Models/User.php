<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Scopes\IsDeleteScope;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    protected $table = 'TT_MEMBER';
    public $timestamps = false;
    protected $primaryKey = 'MEMBER_ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'MEMBER_MAIL_ADDRESS',
        'PASSWORD',
        'MEMBER_ID',
        'MEMBER_STATUS',
        'REGISTER_DATE_TIME',
        'MEDICALINSTITUTION_NAME',
        'MEDICALINSTITUTION_NAME_KANA',
        'MEMBER_LAST_NAME',
        'MEMBER_FIRST_NAME',
        'MEMBER_LAST_NAME_KANA',
        'MEMBER_FIRST_NAME_KANA',
        'POSTAL_CD',
        'PREFECTURE_ID',
        'MUNICIPALITIES',
        'ADDRESS',
        'BUILDING',
        'TELEPHONE',
        'FAX_NUMBER',
        'MEDICALCATEGORY_ID',
        'SECRET_QUESTION_ID',
        'ANSWER_QUESTION',
        'DEPARTMENT',
        'OCCUPATION_ID',
        'SERVICE_CATEGORY_ID',
        'OFFICER_ID',
        'IS_DELETE',
        'IS_DELIVERY',
        'PROXY_INPUT_USER_ID',
        'IS_FIRST_LOGIN',
        'LOCK_TIME',
        'NEW_EMAIL_ADDRESS',
        'AUTH_NUMBER',
        'AUTH_NUMBER_LIMIT',
        'WITHDRAWAL_REASON',
        'OTHER_OPINION',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'PASSWORD',
    ];

    protected $casts = [
        'PASSWORD' => 'hashed',
    ];

    /**
     * Retrieves the JWT identifier for the current instance.
     *
     * @return mixed The JWT identifier.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retrieves the custom claims for JWT.
     *
     * @return array The custom claims for JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Retrieves the authentication password.
     *
     * @return string The authentication password.
     */
    public function getAuthPassword()
    {
        return $this->PASSWORD;
    }

    /**
     * A description of the entire PHP function.
     *
     * @throws Some_Exception_Class description of exception
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }
}
