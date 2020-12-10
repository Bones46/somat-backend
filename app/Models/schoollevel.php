<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $school_level_id
 * @property string $name
 * @property string $description
 * @property string $active_flag
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property ClassMajor[] $classMajors
 * @property School[] $schools
 */
class schoollevel extends SchoolConnectModel
{

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_school_level';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'school_level_id';
    
    /**
     *
     * @var array 
     */
    protected $appends = array('active_status');

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'active_flag', 'created_by', 'created_date', 'updated_by', 'update_date'];

    /**
     *  convert active status from database
     * 
     * @return string
     */
    public function getActiveStatusAttribute()
    {
        return $this->getAttribute('active_flag') == 'Y' ? 'Active' :'Non-Active';
    }

}
