<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property int $school_year_id
 * @property int $school_id
 * @property string $name
 * @property string $start_year
 * @property string $end_year
 * @property string $curriculum_name
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property School $school
 * @property LessonTeacher[] $lessonTeachers
 * @property AcademicCalendar[] $academicCalendars
 * @property Class[] $classes
 */
class academicyear extends SchoolConnectModel
{
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_academic_year';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'academic_year_id';
    
    protected $appends =  array('start_date', 'end_date');

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'name', 'effective_start_date', 'effective_end_date', 'curriculum_name', 'created_by', 'created_date', 'updated_by', 'updated_date'];

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
    public function lessonTeachers()
    {
        return $this->hasMany('App\Models\LessonTeacher', 'school_year_id', 'school_year_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function academicCalendars()
    {
        return $this->hasMany('App\Models\AcademicCalendar', 'school_year_id', 'school_year_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classes()
    {
        return $this->hasMany('App\Models\StudentClass', 'school_year_id', 'school_year_id');
    }
    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function academicPeriod()
    {
        return $this->hasMany('App\Models\AcademicPeriod', 'academic_year_id', 'academic_year_id');
    }
    
    /**
     * 
     * @param type $query
     * @param int $school_id
     * @param int $startYear
     * @param int $endYear
     * @return mixed
     */
    public function scopeFindByRangeYear($query, $school_id, $startYear, $endYear, $academic_year_id =  null)
    {
         $query ->where(function ($query) use ($startYear,$endYear){
                            $query->whereRaw($startYear." between cast (to_char(effective_start_date,'YYYY') as integer) and cast (to_char(effective_start_date,'YYYY') as integer)")
                                  ->orwhereRaw($endYear." between cast (to_char(effective_start_date,'YYYY') as integer) and cast (to_char(effective_start_date,'YYYY') as integer)");
                        })
                ->where('school_id', $school_id);
        
        if($academic_year_id)
          $query->where('academic_year_id', '!=' ,$academic_year_id);
        
        return $query;
    }
    
    /**
     * 
     * @return type
     */
    public function getStartDateAttribute()
    {
         return Carbon::parse($this->getAttribute('effective_start_date'))->format('Y');
    }
    
    /**
     * 
     * @return type
     */
    public function getEndDateAttribute()
    {
        return Carbon::parse($this->getAttribute('effective_end_date'))->format('Y');
    }
    
    /**
     * 
     * @param type $query
     * @param type $school_id
     * @param type $date
     * @return type
     */
    public static function scopeGetByDate($query, $school_id, $date)
    {
        return $query->where('school_id','=', $school_id)
                    ->where(function($query) use ($date){
                        $query->where('effective_start_date', '<=', $date)
                              ->where('effective_end_date', '>=', $date);
                    });
    }
}
