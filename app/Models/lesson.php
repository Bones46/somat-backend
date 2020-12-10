<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int $lesson_id
 * @property int $lesson_category_id
 * @property int $parent_lesson_id
 * @property int $school_id
 * @property int $major_id
 * @property string $name
 * @property string $active_flag
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property LessonCategory $LessonCategory
 * @property ClassMajor $ClassMajor
 * @property Lesson $Lesson
 * @property hool $School
 * @property LessonTeacher[] $LessonTeachers
 */
class Lesson extends SchoolConnectModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_lesson';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'lesson_id';
    
    
    /**
     *
     * @var array
     */
    protected $appends = array('active_status','lesson_name');

    /**
     * @var array
     */
    protected $fillable = ['lesson_category_id', 'parent_lesson_id', 'school_id', 'major_id', 'name', 'active_flag', 'created_by', 'updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lessonCategory()
    {
        return $this->belongsTo('App\Models\LessonCategory', 'lesson_category_id', 'lesson_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classMajor()
    {
        return $this->belongsTo('App\Models\ClassMajor', 'major_id', 'class_major_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lesson()
    {
        return $this->belongsTo('App\Models\Lesson', 'parent_lesson_id', 'lesson_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentLesson()
    {
        return $this->belongsTo('App\Models\Lesson',  'lesson_id', 'parent_lesson_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonTeacher()
    {
        return $this->hasMany('App\Models\LessonTeacher', 'lesson_id', 'lesson_id');
    }
    
    /**
     * get status lesson
     * 
     * @return string
     */
    public function getActiveStatusAttribute()
    {
        return $this->attributes['active_flag'] == 'Y' ? 'Active' :'Non-Active';
    }
    
    /**
     * 
     * 
     * @return string
     */
    public function getLessonNameAttribute()
    {
        if(is_null($this->getAttribute('parent_lesson_id')) )
            return $this->getAttribute('name');
        
        return $this->lesson->name.'('.$this->attributes['name'].')';
    }
    
    /**
     * retrieve lessons other than hierarchy at the top
     * 
     * @param type $query
     * @return type
     */
    public  function scopeGetLesson($query, $school_id)
    {
           return $query
                   ->where('school_id', $school_id)
                   ->whereNotNull('lesson_category_id')
                   ->whereNotExists(function ($query) {
                        $query->select(\DB::raw(1))
                            ->from('sc_lesson as sl')
                            ->whereRaw('sl.parent_lesson_id = sc_lesson.lesson_id');
                    
           });
    }
    
    /**
     * retrieve extra culiculler 
     * 
     * @param type $query
     * @return array
     */
    public  function scopeGetExtCur($query, $school_id)
    {
        return $query->whereNull('lesson_category_id')
                     ->where('school_id', $school_id);
    }
    
    /**
     * 
     * @param type $query
     * @param int $id
     * @return type
     */
    public  function scopeFindByID($query, $id)
    {
        return $query->where('lesson_id', $id)->with('parentLesson');
    }
     
    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            $model->lesson()->detach();
        });
    }
}
