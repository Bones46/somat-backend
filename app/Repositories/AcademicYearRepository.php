<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Repositories\AcademicPeriodRepository;

class AcademicYearRepository extends Repository 
{

    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'AcademicYear', $id, $data, null);
    }
    
    /**
     * 
     * @param type $school_id
     * @return type
     */
    public function getActiveCurrent($school_id)
    {
        return $this->model->getActiveCurrent($school_id)->with('academicPeriod')->first();
    }
    
    /**
     * 
     * @param type $school_id
     * @param type $active
     * @return type
     */
    public function getBySchool($school_id, $active = false) {
        return $this->getBySchoolWith($school_id,'academicPeriod', $active);
    }

    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public function save($school_id, $data)
    {
        DB::BeginTransaction();
        $this->data =  $data['academicyear'];
        $this->data['school_id'] = $school_id;
        
        if($this->data['end_year'] < $this->data['start_year'])
        {
            $this->setErrorMessage('tahun berakhir tidak boleh lebih kecil dari tanggal mulai');
            return false;
        }
       
        $academic= $this->model->findByRangeYear($school_id, 
                                                 $this->data['start_year'],
                                                 $this->data['end_year'], 
                                                 $this->data['academic_year_id'])->first();
        
        if( $academic && ($academic->end_year != $this->data['start_year'] && $academic->start_year != $this->data['end_year']))
        {
            $this->setErrorMessage('Tahun Periode sudah di gunakan');
            return false;
        }
        
        if(!$this->store())
            return false;
        
        if(!$result = AcademicPeriodRepository::save($school_id, $this->getModel(), $data['academicperiod']))
        {
            DB::rollback();
            $this->setErrorMessage(AcademicPeriodRepository::error());
            return false;
        }
        
        $this->model['academicperiod'] = $result;
        DB::commit();
        
        return true;
    }
    
    /**
     * 
     * pengechekan tahun yang entry 
     * 
     * telah di gunakan di periode akademik yang lain
     * 
     * @param int $startYear
     * @return boolean
     */
    public function isRange($school_id, $startYear, $endyear, $academic_year_id)
    {
        return $this->getModel()->findByRangeYear($school_id, $startYear,$endyear,$academic_year_id)->get()->count() > 0;
    }
}