<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $school_month_id
 * @property int $school_id
 * @property string $name
 * @property int $month_number
 * @property string $description
 * @property int $year_number
 * @property int $quarter_number
 * @property string $start_date
 * @property string $end_date
 * @property string $status
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property School $school
 */
class schoolmonth extends Model
{
    /** 
     *  The constant name of default timestamps field laravel
     * 
     *  @var string
    */

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'update_date';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'school_month';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'school_month_id';

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'name', 'month_number', 'description', 'year_number', 'quarter_number', 'start_date', 'end_date', 'status', 'created_by', 'created_date', 'update_by', 'update_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'school_id');
    }
}
