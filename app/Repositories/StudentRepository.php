<?php

namespace App\Repositories;

use Illuminate\Support\Arr;
use App\Repositories\DataRepository;
use App\Models\academicyear;
use App\Models\usermapping;
use Illuminate\Support\Facades\DB;


class StudentRepository extends Repository
{
    const FLAG_YES = 'Y';
    const FLAG_NO = 'N';
    
    protected $registration;

    public function __construct(RegistrationRepository $repository) {
        parent::__construct('students');
        $this->registration = $repository;
    }

    public function createNew(request $request)
    {   
        $arrRequest = $request->all();

        /***************************************************************************
        * Split arrRequest to subkey request => Student
        ***************************************************************************/       
        $studentRequest = Arr::get($arrRequest, 'student.student');
        $locationRequest = Arr::get($arrRequest, 'student.location');
        $profileRequest = Arr::get($arrRequest, 'student.profile');

        $keylocation_id = 'location_id';
        $keystudent = 'student_id';
        $keylocation = 'location_id';
        $keyuser = 'user_id';
        $keyprofile = 'user_profile_id';
        $keymapping = 'user_mapping_id';
   
        /************************************
        * Save or Update table Location
        ************************************/ 

        if (!(is_null($profileRequest[$keylocation]))) {
            $locationRequest[$keylocation] = $profileRequest[$keylocation];
        }

        $idlocation = DataRepository::SaveOrEdit($keylocation,$locationRequest,'locations');

        /************************************
        * Save or Update table User
        ************************************/ 

        $arruser = DataRepository::CreateUser($keyuser,$profileRequest);

        $iduser = DataRepository::SaveOrEdit($keyuser,$arruser,'user');  
        
        /************************************
        * Cek file image 
        **************************************/
        if ($request->hasFile('student.profile.profile_image')) {
            $uploadedFile = $request->file('student.profile.profile_image');
            $path = $uploadedFile->store('public/files');
            $profileRequest['profile_image'] = $path;
        }

        /************************************
        * Save or Update table Profile
        ************************************/ 
        $profileRequest[$keylocation] = $idlocation;
        $profileRequest[$keyuser] = $iduser;
        unset($profileRequest['email']);
        $idStudentProfile = DataRepository::SaveOrEdit($keyprofile,$profileRequest,'user_profile');

        /************************************
        * Save or Update table Student
        ************************************/ 
        $studentRequest['person_id'] = $idStudentProfile;

        $idstudent = DataRepository::SaveOrEdit($keystudent,$studentRequest,'students'); 
        
        /***************************************************************************
        * Split arrRequest to subkey request => Parent
        ***************************************************************************/

        $count = count($arrRequest['parent']);
        $parent = array();
        for ($i=0;$i<$count;$i++) {

            $locationRequest = Arr::get($arrRequest, 'parent.'.$i.'.location');
            $profileRequest = Arr::get($arrRequest, 'parent.'.$i.'.profile');

            /************************************
            * Save or Update table Location
            ************************************/ 
            if (!(is_null($profileRequest[$keylocation]))) {
                $locationRequest[$keylocation] = $profileRequest[$keylocation];
            }
            $idlocation = DataRepository::SaveOrEdit($keylocation,$locationRequest,'locations');

            /************************************
            * Save or Update table User
            ************************************/ 
            $arruser = DataRepository::CreateUser($keyuser,$profileRequest);

            $iduser = DataRepository::SaveOrEdit($keyuser,$arruser,'user');

            /************************************
            * Cek file image 
            **************************************/
            if ($request->hasFile('parent.'.strval($i).'.profile.profile_image')) {
                $uploadedFile = $request->file('parent.'.strval($i).'.profile.profile_image');
                $path = $uploadedFile->store('public/files');
                $profileRequest['profile_image'] = $path;
            }

            /************************************
            * Save or Update table Profile
            ************************************/ 
            $family_relation = $profileRequest['family_relation'];
            $profileRequest[$keylocation] = $idlocation;
            $profileRequest[$keyuser] = $iduser;
            unset($profileRequest['email']);
            unset($profileRequest['family_relation']);
            $idParentProfile = DataRepository::SaveOrEdit($keyprofile,$profileRequest,'user_profile');    
            

            /***********************************
            * Save USer Mapping 
            ************************************/ 
            $idmapping = usermapping::where('mapping_code_name',$family_relation)
                    ->where('person_id',$idStudentProfile)->where('join_person_id',$idParentProfile)
                    ->get($keymapping);

            if (array_key_exists($keymapping,$idmapping)) {
                $usermapping[$keymapping] = $idmapping;
            }        
            $usermapping['person_id'] = $idStudentProfile;
            $usermapping['join_person_id'] = $idParentProfile;
            $usermapping['mapping_code_name'] = $family_relation;

            $idParentProfile = DataRepository::SaveOrEdit($keymapping,$usermapping,'user_mapping');    
 
        }

        return $idstudent;
    }
    
    /**
     * save to storege student
     * 
     * @param array $student
     * @return boolean
     */
    public function save($school_id, $user_id, $student)
    {
        $student['person_id'] = $user_id;
        $student['school_id'] = $school_id;
        
        $this->data = $student;
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
        
        if(!$this->save($school_id, $user->user_profile_id, $request->input('student')))
        {
            DB::rollback();
            return false;
        }
        
        if(!$request->input('parent'))
        {
            DB::commit();
            return $user; 
        }
        
        $parents = array();
        foreach($request->input('parent') as $parent)
        {   
            $location =  $parent['location'];         
            if(is_null($parent['location']['address']))
                $location =$request->input('location');
            
            $profile =  $this->registration->createUser($school_id, $parent['profile'],$location);
            if(!$profile)
            {
                $this->setErrorMessage($this->registration->getErrorMessage());
                DB::rollback();
                return $profile;
            }
            
            $user->family()->detach(); 
            $user->family()->attach( $profile->user_profile_id, ['related_type_name'    => $parent['profile']['relationship'], 
                                                                 'primary_flag'      => $parent['profile']['primary_flag']]
                                );
            $parents[] = $profile;
        }
        DB::commit();
        
        $user['family'] = $parents;
        $this->getModel()['profile'] = $user;
        
        return true;
    }
}