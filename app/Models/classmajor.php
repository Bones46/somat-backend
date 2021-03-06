<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $class_major_id
 * @property int $school_level_id
 * @property string $name
 * @property string $description
 * @property string $active_flag
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property SchoolLevel $schoolLevel
 * @property Lesson[] $lessons
 * @property Class[] $classes
 */
class classmajor extends SchoolConnectModel
{  
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_class_major';

    /**
     * the key of extended variable view
     * 
     * @var type 
     */
    protected $appends =  array('active_status');

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'class_major_id';

    /**
     * @var array
     */
    protected $fillable = ['school_level_id', 'name', 'description', 'major_code','school_id', 'active_flag', 'code', 'created_by', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolLevel()
    {
        return $this->belongsTo('App\Models\SchoolLevel', 'school_level_id', 'school_level_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Models\Lesson', 'major_id', 'class_major_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classes()
    {
        return $this->hasMany('App\Models\Classes', 'class_major_id', 'class_major_id');
    }

     /**
     * get status class major
     * 
     * @return string
     */
    public function getActiveStatusAttribute()
    {
        return $this->attributes['active_flag'] == 'Y' ? 'Active' :'Non-Active';
    }

    
}
