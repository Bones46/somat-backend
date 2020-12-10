<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $job_assignment_id
 * @property int $job_id
 * @property int $school_id
 * @property int $employee_id
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Employee $employee
 * @property Job $job
 * @property School $school
 */
class jobassignment extends Model
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
    protected $table = 'job_assignment';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'job_assignment_id';

    /**
     * @var array
     */
    protected $fillable = ['job_id', 'school_id', 'employee_id', 'effective_start_date', 'effective_end_date', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employees', 'employee_id', 'employee_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function job()
    {
        return $this->belongsTo('App\Models\Jobs', 'job_id', 'job_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'school_id');
    }
}
