<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seminar_Details extends Model
{
    use HasFactory;

    protected $table = 'TT_SEMINAR_DETAILS';
    protected $primaryKey = 'SEMINAR_DETAIL_ID';

    public $timestamps = false;

    protected $fillable = [
        'SEMINAR_ID',
        'DISPLAY_ORDER',
        'HEADLINE',
        'CONTENTS',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    /**
     *
     * @throws Exception_Class description of exception
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }
}
