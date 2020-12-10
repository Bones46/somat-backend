<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $school_id
 * @property int $school_level_id
 * @property integer $head_master_person_id
 * @property integer $location_id
 * @property string $name
 * @property string $registration_number
 * @property string $image
 * @property string $type
 * @property string $acreditation
 * @property string $email
 * @property string $website
 * @property string $phone_number
 * @property string $foundation_name
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Location $location
 * @property SchoolLevel $schoolLevel
 * @property AcademicPeriod[] $academicPeriods
 * @property JobAssignment[] $jobAssignments
 * @property LessonTeacher[] $lessonTeachers
 * @property SchoolRegNo[] $schoolRegNos
 * @property SchoolMonth[] $schoolMonths
 * @property TimeSetup[] $timeSetups
 * @property Lesson[] $lessons
 * @property SchoolYear[] $schoolYears
 * @property Class[] $classes
 * @property PostCategory[] $postCategories
 * @property Post[] $posts
 */
class school extends Model
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
    protected $table = 'sc_school';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'school_id';
    
    protected $appends = array('study_time_status', 'school_status', 'education_level');

    /**
     *
     * @var type 
     */
    protected $fillable = ['school_level_id',  'location_id', 'name', 'registration_number', 'image', 'study_time', 'acreditation', 'email', 'website',
                           'phone_number','status', 'foundation_name', 'effective_start_date','effective_end_date','created_by', 'created_date', 'updated_by', 'updated_date'];
     

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->hasOne('App\Models\Locations', 'location_id', 'location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolLevel()
    {
        return $this->hasOne('App\Models\SchoolLevel', 'school_level_id', 'school_level_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function academicPeriods()
    {
        return $this->hasMany('App\Models\AcademicPeriod', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobAssignments()
    {
        return $this->hasMany('App\Models\JobAssignment', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonTeachers()
    {
        return $this->hasMany('App\Models\LessonTeacher', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schoolRegNo()
    {
        return $this->hasMany('App\Models\SchoolRegNo', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schoolMonths()
    {
        return $this->hasMany('App\Models\SchoolMonth', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeSetups()
    {
        return $this->hasMany('App\Models\TimeSetup', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Models\Lesson', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schoolYears()
    {
        return $this->hasMany('App\Models\SchoolYear', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classes()
    {
        return $this->hasMany('App\Models\Classes', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postCategories()
    {
        return $this->hasMany('App\Models\PostCategory', 'school_id', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'school_id', 'school_id');
    }
    
    /**
     * 
     * @param type $query
     * @param string  $schoolLvl 
     */
    public function scopeGetBySchoolLevel($query, $id)
    {
        return $query->where('school_level_id', $id);
    }
    
    /**
     * get status waktu kegiatan 
     * 
     * @return string
     */
    public function getStudyTimeStatusAttribute()
    {
        switch ($this->getAttribute('study_time'))
        {
            case 'P':
                return 'Pagi';
            case 'S':
                return 'Siang';
            case 'B':
                return 'Pagi dan Siang';
            default:
                return null;
        }
    }
    
    /**
     * 
     * @return String
     */
    public function getSchoolStatusAttribute()
    {
        return $this->getAttribute('status') == 'N'? 'Negeru' : 'Swasta';
    }
    
    /**
     * get education level 
     * 
     * @return object
     */
    public function getEducationLevelAttribute()
    {
        return $this->schoolLevel()->first();
    }
}
