<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $class_id
 * @property int $class_major_id
 * @property int $school_year_id
 * @property int $school_id
 * @property integer $teacher_id
 * @property int $category_class_id
 * @property string $class_name
 * @property string $class_level
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property ClassMajor $classMajor
 * @property School $school
 */
class classes extends SchoolConnectModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_class';


    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'class_id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';
    
    protected $appends = array('major');

    /**
     * @var array
     */
    protected $fillable = ['class_name', 'class_level', 'class_major_id', 'school_id', 'category_class_id', 'class_code', 'active_flag', 'created_by', 'update_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classMajor()
    {
        return $this->belongsTo('App\Models\ClassMajor', 'class_major_id', 'class_major_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lessonCategory()
    {
        return $this->belongsTo('App\Models\LessonCategory', 'category_class_id', 'lesson_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonTeachers()
    {
        return $this->hasMany('App\Models\LessonTeacher', 'class_id', 'class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scheduleClasses()
    {
        return $this->hasMany('App\Models\ScheduleClass', 'class_id', 'class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function academicCalendars()
    {
        return $this->hasMany('App\Models\AcademicCalendar', 'class_id', 'class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentClasses()
    {
        return $this->hasMany('App\Models\StudentClass', 'class_id', 'class_id');
    }    
    
    public function getMajorAttribute()
    {
        return $this->classMajor()->first();
    }
}
