<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property int $academic_period_id
 * @property int $school_id
 * @property string $name
 * @property string $periode_start_month
 * @property string $periode_end_month
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property School $school
 */
class academicperiod extends Model
{
    /** 
     *  The constant name of default timestamps field laravel
     * 
     *  @var string
    */
    
   protected $appends = array('current_active');

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_academic_period';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'academic_period_id';

    /**
     * @var array
     */
    protected $fillable = ['period_name', 'academic_year_id', 'effective_start_date', 'effective_end_date', 'created_by', 'created_date', 'updated_by', 'updated_date'];

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
    public function schoolYear()
    {
        return $this->belongsTo('App\Models\School', 'school_year_id', 'school_year_id');
    }
    
    /**
     *  active periode
     * 
     * @return boolean
     */
    public function getCurrentActiveAttribute()
    {
         $active = \now() >= $this->getAttribute('effective_start_date') && \now() <= $this->getAttribute('effective_end_date');
         return $active ? 'Y' : 'N';
    }
    
    /**
     * 
     * @param type $query
     * @param date $startDate, date of effective start date academic period
     */
    public function scopeFindByStartDate($query, $school_year_id, $startDate, $academic_period_id = null)
    {
        $query->wherebetween($startDate, ['effective_start_date ','effective_end_date'])
              ->whereRaw('school_year_id', $school_year_id);
        
        if(!is_null($academic_period_id ))
            $query->where('academic_period_id !=' .$academic_period_id);
        
        return $query->get();
    }
    
    /**
     * 
     * @param type $query
     * @param date $startDate
     * @param date $endDate
     */
    public function scopeFindByDate($query, $school_id, $startDate, $endDate, $academic_period_id = null)
    {
        
        $query ->join('sc_academic_year', 'sc_academic_year.academic_year_id','=',$this->table.'.academic_year_id')
                ->where(function ($query) use ($startDate,$endDate){                 
                        $query->whereRaw( "'$startDate'" .' between effective_start_date and effective_end_date')
                              ->orWhereRaw( "'$endDate'"  .' between effective_start_date and effective_end_date');
                })
                ->where('sc_academic_year.school_id', $school_id);
         
        if(!is_null($academic_period_id ))
          $query->whereRaw('academic_period_id !=' .$academic_period_id);
        
        return $query->get();
    }
}
