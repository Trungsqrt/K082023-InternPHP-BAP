<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Icon extends Model
{
    use HasFactory;

    protected $table = 'TM_ICON';
    protected $primaryKey = 'ICON_ID';

    public $timestamps = false;

    protected $fillable = [
        'ICON_NAME',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    /**
     * Initializes the function and adds a global scope for filtering deleted records.
     *
     * @throws Some_Exception_Class if an error occurs while adding the global scope.
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }

    /**
     * Retrieve the seminar icons associated with the current object.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seminar_icons()
    {
        return $this->hasMany(Seminar_Icon::class, "ICON_ID");
    }
}
