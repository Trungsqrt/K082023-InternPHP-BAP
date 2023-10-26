<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seminar_Mail_Info extends Model
{
    use HasFactory;

    protected $table = 'TT_SEMINAR_MAIL_INFO';
    protected $primaryKey = 'SEMINAR_MAIL_INFO_ID';

    public $timestamps = false;

    protected $fillable = [
        'SEMINAR_ID',
        'MAIL_CATEGORY',
        'OPTIONAL_MESSAGE_HALL',
        'OPTIONAL_MESSAGE_ONLINE',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    /**
     * Initializes the booting process for the class.
     *
     * @throws Exception_Class description of exception
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }
}
