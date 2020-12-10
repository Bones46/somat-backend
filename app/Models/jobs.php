<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $job_id
 * @property int $position_id
 * @property string $job_name
 * @property string $grade
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Position $position
 * @property JobAssignment[] $jobAssignments
 */
class jobs extends SchoolConnectModel
{    
    /**
     *
     * @var string 
     */
    protected $table = 'sc_jobs';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'job_id';

    /**
     * @var array
     */
    protected $fillable = ['position_id','school_id', 'job_name', 'grade', 'effective_start_date', 'effective_end_date', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position()
    {
        return $this->belongsTo('App\Models\Position', 'position_id', 'position_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobAssignments()
    {
        return $this->hasMany('App\Models\JobAssignment', 'job_id', 'job_id');
    }
    
    /**
     * get job by position
     * 
     * @param type $query
     * @param int $school_id
     * @param int $position_id
     * @return type
     */
    public function scopeGetByPosition($query, $school_id, $position_id)
    {
        return $query->where('school_id','=',$school_id)
                     ->where('position_id','=',$position_id)
                     ->get();
    }
}
