<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;


class EmployeeRepository extends Repository
{    
    protected $registration;

    public function __construct(RegistrationRepository $repository) {
        parent::__construct('employees');
        $this->registration = $repository;
    }
    
    /**
     * save to storege student
     * 
     * @param array $student
     * @return boolean
     */
    public function save($school_id, $user_id, $employee)
    {
        $employee['person_id'] = $user_id;
        $employee['school_id'] = $school_id;
        
        $this->data = $employee;
        return $this->store();
    }
    
    /**
     * 
     * @param type $request
     * @return type
     */   
    public function Register($request)
    {
        $school_id = $request->input('school_id');
        DB::BeginTransaction();
        $user =  $this->registration->createUser($school_id, $request->input('profile'), $request->input('location'));
        if(!$user)
        {
            $this->setErrorMessage($this->registration->getErrorMessage());
            return $user;
        }
        
        $employees = $request->input('employee');
        
        if(!$this->save($school_id, $user->user_profile_id, $employees))
        {
            DB::rollback();
            return false;
        }
        
        $this->getModel()->classification()->sync($employees['jobs']);
        DB::commit();
        $this->getModel()['profile'] = $user;
        return true;
    }
    
    /**
     * 
     * @param int $school_id
     * @return type
     */
    public function getTeacher($school_id)
    {
        return $this->model->getTeacher($school_id);
    }
}