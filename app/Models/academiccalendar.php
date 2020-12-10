<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $academic_calendar_id
 * @property int $school_year_id
 * @property int $class_id
 * @property string $event_date
 * @property string $description
 * @property string $category
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property SchoolYear $schoolYear
 * @property Class $class
 */
class academiccalendar extends Model
{
    /** 
     *  The constant name of default timestamps field laravel
     * 
     *  @var string
    */

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'academic_calendar';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'academic_calendar_id';

    /**
     * @var array
     */
    protected $fillable = ['school_year_id', 'class_id', 'event_date', 'description', 'event_name', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolYear()
    {
        return $this->belongsTo('App\Models\SchoolYear', 'school_year_id', 'school_year_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classes()
    {
        return $this->belongsTo('App\Models\Classes', 'class_id', 'class_id');
    }
}
