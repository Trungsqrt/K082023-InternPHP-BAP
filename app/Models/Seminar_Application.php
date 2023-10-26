<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seminar_Application extends Model
{
    use HasFactory;

    protected $table = 'TT_SEMINAR_APPLICATION';
    protected $primaryKey = 'SEMINAR_APPLICATION_ID';

    public $timestamps = false;

    protected $fillable = [
        'SEMINAR_APPLICATION_ID',
        'SEMINAR_ID',
        'MEMBER_ID',
        'SEMINAR_APPLICATION_CATEGORY',
        'QUESTIONNAIRE_ANSWER_ID',
        'IS_DELETE',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    /**
     * Retrieve the seminar that this belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'SEMINAR_ID');
    }

    /**
     * @throws Exception_Class description of exception
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }
}
