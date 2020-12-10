<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $schedule_class_id
 * @property integer $class_id
 * @property integer $teacher_id
 * @property int $lesson_teacher_id
 * @property string $day_schedule
 * @property string $start_time
 * @property string $end_time
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Class $class
 * @property LessonTeacher $lessonTeacher
 * @property UserProfile $userProfile
 */
class scheduleclass extends Model
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
    protected $table = 'schedule_class';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'schedule_class_id';

    /**
     * @var array
     */
    protected $fillable = ['class_id', 'lesson_teacher_id', 'study_day', 'start_time', 'end_time', 'activity_id' ,'created_by', 'created_date', 'updated_by', 'updated_date'];

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
    public function lessonTeacher()
    {
        return $this->belongsTo('App\Models\LessonTeacher', 'lesson_teacher_id', 'lesson_teacher_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userProfile()
    {
        return $this->belongsTo('App\Models\UserProfile', 'teacher_id', 'user_profile_id');
    }
}
