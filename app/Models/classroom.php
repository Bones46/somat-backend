<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $student_class_id
 * @property int $class_id
 * @property integer $student_id
 * @property integer $parent_student_class_id
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Class $class
 * @property StudentClass $studentClass
 * @property Student $student
 */
class classroom extends Model
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
    protected $table = 'classroom';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'classroom_id';

    /**
     * @var array
     */
    protected $fillable = ['class_id', 'teacher_id', 'school_year_id','class_time', 'created_by', 'created_date', 'updated_by', 'update_ddate'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classes()
    {
        return $this->belongsTo('App\Models\Classes', 'class_id', 'class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id', 'student_id');
    }
}
