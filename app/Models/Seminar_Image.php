<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seminar_Image extends Model
{
    use HasFactory;

    protected $table = 'TT_SEMINAR_IMAGE';
    protected $primaryKey = 'SEMINAR_IMAGE_ID';

    public $timestamps = false;

    protected $fillable = [
        'SEMINAR_IMAGE_ID',
        'SEMINAR_ID',
        'IMAGE_CATEGORY',
        'DISPLAY_ORDER',
        'FILE_NAME',
        'FILE_PATH',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    /**
     * Initializes the function to be executed when the model is booted.
     *
     * @throws Exception_Class description of exception
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }
}
