<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property int $employee_id
 * @property integer $person_id
 * @property int $school_id
 * @property string $nip
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property string $nupt
 * @property string $classification_name
 * @property UserProfile $userProfile
 * @property JobAssignment[] $jobAssignments
 */
class employees extends SchoolConnectModel
{
    protected $table = 'sc_employees';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'employee_id';

    /**
     * @var array
     */
    protected $fillable = ['person_id', 'school_id', 'nip', 'effective_start_date', 'effective_end_date', 'created_by', 'created_date', 'updated_by', 'updated_date', 'nuptk'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Profile()
    {
        return $this->belongsTo('App\Models\UserProfile', 'person_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobAssignments()
    {
        return $this->hasMany('App\Models\JobAssignment', 'employee_id', 'employee_id');
    }
    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classification()
    {
        return $this->belongsToMany('App\Models\Classification','sc_emp_classification_map','employee_id','classification_id');
    }
    
    /**
     * 
     * @param type $query
     * @param int  $school
     */
    public function scopeGetTeacher($query, $school_id)
    {
        return $query->where('school_id', '=', $school_id)
                     ->whereRaw("now() > effective_start_date and now()< coalesce(effective_end_date, now() + INTERVAL '1' DAY)")
                     ->whereHas('classification', function($query){
                                $query->where('classification_name', '=', 'Pendidik');
                        })
                     ->with('profile')
                     ->get();
    }
}
