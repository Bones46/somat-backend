<?php

namespace App\Repositories;

use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AcademicPeriodRepository extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'AcademicPeriod', $id, $data, null);
    }

    
    /**
     * retrieve All Class Major 
     * 
     * @return array
     */
    public static function getSchoolYear()
    {       
        return $this->activecurrent()->first();
    }

    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public static function save( $school_id, $academicYear,$data)
    {
        $return = array();
        DB::BeginTransaction();
        
        
        foreach ($data as $key => $item)
        {            
            $academic = new AcademicPeriodRepository(null, $item);
            if($academic->data['effective_end_date'] < $academic->data['effective_start_date'])
            {
                $academic->setErrorMessage('tanggal berakhir tidak boleh lebih awal dari tanggal mulai');
                return false;
            }
            
            $startYear = Carbon::parse($item['effective_start_date'])->format('Y');
            $endYear = Carbon::parse($item['effective_end_date'])->format('Y');
            
            if(!($startYear >= $academicYear->start_year && $startYear <= $academicYear->end_year)&&
               !($endYear >=  $academicYear->start_year && $endYear <= $academicYear->end_year ))
            {
                $academic->setErrorMessage('Periode Tanggal harus diantara Tahun Periode');
                return false;
            }
            
            if($academic->getModel()->findByDate( $school_id,
                                                  $academic->data['effective_start_date'], 
                                                  $academic->data['effective_end_date'],
                                                  $academic->data['academic_period_id'])->count() >0)
            {
                $academic->setErrorMessage('Periode Tanggal talah digunakan');
                return false;
            }
            
            $academic->data['academic_year_id'] = $academicYear['academic_year_id'];
            if(!$academic->store())
            {
                DB::rollback();
                return false;
            }
            
            if(is_null($academicYear->effective_start_date) || $academic->getModel()->effective_start_date < $academicYear->effective_start_date )
                $academicYear->effective_start_date = $academic->getModel()->effective_start_date;
            
            if(is_null($academicYear->effective_end_date) || $academic->getModel()->effective_end_date < $academicYear->effective_end_date)
                $academicYear->effective_end_date = $academic->getModel()->effective_end_date;
            
            $return[] = $academic->getModel();
        }
        
        $academicYear->store();
        
        DB::commit();
        return $return;
    }
    
    /**
     * 
     * pengechekan tahun yang entry 
     * telah di gunakan di periode akademik yang lain
     * 
     * @param int $startYear
     * @return boolean
     */
    public function isRange($school_id, $startYear)
    {
        return $this->getModel()->findByStartYear($school_id, $startYear)->get()->count() > 0;
    }
}