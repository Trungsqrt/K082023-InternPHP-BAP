<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Questionnaire extends Model
{
    use HasFactory;

    protected $table = 'TT_QUESTIONNAIRES';
    protected $primaryKey = 'QUESTIONNAIRE_ID';

    public $timestamps = false;

    protected $fillable = [
        'QUESTIONNAIRE_ID',
        'DISPLAY_CATEGORY',
        'MEDICAL_CATEGORY_ID',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    /**
     * @throws Exception_Class description of exception
     * @return Some_Return_Value
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }
}
