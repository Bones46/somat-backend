<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/**
 * @property integer $time_setup_id
 * @property int $school_id
 * @property string $start_time
 * @property string $end_time
 * @property string $time_category
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property School $school
 */
class lessontime extends SchoolConnectModel
{

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_lesson_time';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'lesson_time_id';

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'start_time', 'end_time', 'study_time', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'school_id');
    }
    
}
