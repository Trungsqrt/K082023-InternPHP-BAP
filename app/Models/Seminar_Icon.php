<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seminar_Icon extends Model
{
    use HasFactory;

    protected $table = 'TR_SEMINAR_ICON';
    protected $primaryKey = 'SEMINAR_ICON_ID';

    public $timestamps = false;

    protected $fillable = [
        'SEMINAR_ID',
        'ICON_ID',
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

    /**
     * Retrieves the icon associated with this instance.
     *
     * @return Icon The icon associated with this instance.
     */
    public function icon()
    {
        return $this->belongsTo(Icon::class, 'ICON_ID');
    }

    /**
     * Retrieve the Seminar model that belongs to this instance.
     *
     * @return Seminar The Seminar model that belongs to this instance.
     */
    public function seminar()
    {
        return $this->belongsTo(Seminar::class, 'SEMINAR_ID');
    }
}
