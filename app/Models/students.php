<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\academicyear;
use Carbon\Carbon;

/**
 * @property integer $student_id
 * @property integer $person_id
 * @property int $school_id
 * @property string $nisn
 * @property string $nis
 * @property string $certificate_number
 * @property string $skhun
 * @property integer $son_of
 * @property string $class_level
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property UserProfile $userProfile
 * @property StudentClass[] $studentClasses
 */
class students extends SchoolConnectModel
{

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'student_id';
    
    protected $table = 'sc_students';
    
    protected $appends = array('academic_start', 'academic_end');

    /**
     * @var array
     */
    protected $fillable = ['person_id', 'school_id', 'nisn', 'nis', 'certificate_number', 'skhun', 'son_of', 'class_level', 'effective_start_date', 'effective_end_date',
                           'passed_flag','mutation_flag', 'created_by', 'created_date', 'updated_by', 'update_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profile()
    {
        return $this->belongsTo('App\Models\UserProfile', 'person_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentClasses()
    {
        return $this->hasMany('App\Models\StudentClass', 'student_id', 'student_id');
    }
    
    /**
     * 
     */
    public function getAcademicYear($date)
    {
        return academicyear::getByDate($this->getAttribute('school_id'), $date);
    }
    
    /**
     * 
     */
    public function getAcademicStartAttribute()
    {
        return $this->getAcademicYear($this->getAttribute('effective_start_date'))->first();
    }
    
    /**
     * 
     */
    public function getAcademicEndAttribute()
    {
        if(is_null($this->getAttribute('effective_end_date')))
            return null;
        
        return $this->getAcademicYear($this->getAttribute('effective_end_date'))->first();
    }
    
    
}
