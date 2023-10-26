<?php

namespace App\Models;

use App\Models\Scopes\IsDeleteScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seminar extends Model
{
    use HasFactory;

    protected $table = 'TT_SEMINAR';
    protected $primaryKey = 'SEMINAR_ID';
    public $timestamps = false;

    protected $fillable = [
        'SEMINAR_ID',
        'SEMINAR_TITLE',
        'IS_HALL_SEMINAR',
        'IS_ONLINE_SEMINAR',
        'EVENT_STARTDATE',
        'EVENT_ENDDATE',
        'PUBLICATION_START_DATE_TIME',
        'PUBLICATION_END_DATE_TIME',
        'LIST_OVERVIEW',
        'SEMINAR_MAXIMUM_PARTICIPANT',
        'ONLINE_VIEW_URL',
        'QUESTIONNAIRE_ID',
        'IS_DELETE',
        'CREATE_FUNC_ID',
        'CREATE_PERSON_ID',
        'CREATE_DATE_TIME',
        'UPDATE_FUNC_ID',
        'UPDATE_PERSON_ID',
        'UPDATE_DATE_TIME',
    ];

    /**
     * Retrieve the applications associated with the seminar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications()
    {
        return $this->hasMany(Seminar_Application::class, "SEMINAR_ID");
    }

    /**
     * Get the seminar icons associated with the seminar.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function seminar_icons()
    {
        return $this->hasMany(Seminar_Icon::class, "SEMINAR_ID");
    }

    /**
     * Filter the query by keyword.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder
     * @param string $keyword The keyword to filter by
     * @return \Illuminate\Database\Eloquent\Builder The filtered query builder
     */
    public function scopeFilterByKeyword($query, $keyword)
    {
        return $query->where('SEMINAR_TITLE', 'LIKE', '%' . $keyword . '%');
    }

    /**
     * Filters the query by category.
     *
     * @param mixed $query The query to filter.
     * @param string $category The category to filter by.
     * @return mixed The filtered query.
     */
    public function scopeFilterByCategory($query, $category)
    {
        if ($category == 'offline')
            return $query->where('IS_HALL_SEMINAR', 1)
                ->where('IS_ONLINE_SEMINAR', 0);

        else if ($category == 'online')
            return $query->where('IS_HALL_SEMINAR', 0)
                ->where('IS_ONLINE_SEMINAR', 1);

        return $query->where('IS_HALL_SEMINAR', 1)
            ->where('IS_ONLINE_SEMINAR', 1);
    }

    /**
     *
     * @throws Some_Exception_Class description of exception
     * @return Some_Return_Value
     */
    protected static function booted()
    {
        static::addGlobalScope(new IsDeleteScope);
    }
}
