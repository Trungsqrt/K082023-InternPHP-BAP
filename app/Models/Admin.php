<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    protected $table = 'TT_EMPLOYEES';
    public $timestamps = false;
    protected $primaryKey = 'EMPLOYEE_ID';

    protected $fillable = [
        'EMPLOYEE_MAIL_ADDRESS',
        'EMPLOYEE_PASSWORD',
        'EMPLOYEE_ID',
        'EMPLOYEE_NAME',
        'IS_FIRST_LOGIN',
        'AUTHORITY',
        'IS_DELETE',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    protected $hidden = [
        'EMPLOYEE_PASSWORD',
    ];

    protected $casts = [
        'EMPLOYEE_PASSWORD' => 'hashed',
    ];

    /**
     * Retrieves the JWT identifier for the PHP function.
     *
     * @return datatype The JWT identifier.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Retrieves the custom claims for the JWT.
     *
     * @return array The custom claims for the JWT.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Retrieves the authentication password for the employee.
     *
     * @return string The authentication password.
     */
    public function getAuthPassword()
    {
        return $this->EMPLOYEE_PASSWORD;
    }

    /**
     * A description of the entire PHP function.
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }
}
