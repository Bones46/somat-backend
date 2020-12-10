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
class studentclass extends Model
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
    protected $table = 'student_class';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'student_class_id';

    /**
     * @var array
     */
    protected $fillable = ['class_id', 'class_time', 'created_by', 'created_date', 'update_by', 'update_date'];

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function childStudentClass()
    {
        return $this->belongsTo('App\Models\StudentClass', 'parent_student_class_id', 'student_class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parentStudentClass()
    {
        return $this->hasMany('App\Models\student_class','parent_student_class_id','student_class_id');
    }
}
