<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $position_id
 * @property string $position_name
 * @property string $description
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Job[] $jobs
 */
class position extends SchoolConnectModel
{

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_position';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'position_id';

    /**
     * @var array
     */
    protected $fillable = ['position_id', 'organization_id', 'school_id', 'name', 'description', 'effective_start_date', 'effective_end_date', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs()
    {
        return $this->hasMany('App\Models\Jobs', 'position_id', 'position_id');
    }
    
    /**
     * get position by organization 
     * 
     * @param type $query
     * @param int $school_id
     * @param int $org_id
     * @return type
     */
    public function scopeGetByOrganization($query,$school_id, $org_id)
    {
        return $query->where('school_id','=',$school_id)
                     ->where('organization_id','=',$org_id)
                     ->get();
    }
}
