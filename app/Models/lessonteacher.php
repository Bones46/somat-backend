<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $lesson_teacher_id
 * @property integer $lesson_id
 * @property integer $teacher_id
 * @property int $school_year_id
 * @property int $class_id
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property string $class_level
 * @property Lesson $lesson
 * @property SchoolYear $schoolYear
 * @property UserProfile $userProfile
 * @property Class $class
 * @property ScheduleClass[] $scheduleClasses
 */
class lessonteacher extends Model
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
    protected $table = 'lesson_teacher';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'lesson_teacher_id';

    /**
     * @var array
     */
    protected $fillable = ['lesson_id', 'teacher_id', 'school_year_id', 'class_id', 'created_by', 'created_date', 'updated_by', 'updated_date', 'class_level'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo('App\Models\Lesson', 'lesson_id', 'lesson_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolYear()
    {
        return $this->belongsTo('App\Models\SchoolYear', 'school_year_id', 'school_year_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userProfile()
    {
        return $this->belongsTo('App\Models\UserProfile', 'teacher_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classes()
    {
        return $this->belongsTo('App\Models\Classes', 'class_id', 'class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scheduleClasses()
    {
        return $this->hasMany('App\Models\ScheduleClass', 'lesson_teacher_id', 'lesson_teacher_id');
    }
}
