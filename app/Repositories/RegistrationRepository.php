<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

use App\Repositories\UserProfileRepository;
use App\Repositories\UserRepository;
use App\Repositories\LocationRepository;

class RegistrationRepository 
{
    const FLAG_YES = 'Y';
    const FLAG_NO = 'N';
    
    public $error;
    
    /**
     * 
     * @param array $profile
     * @param array $location
     * @return boolean
     */
    public function createUser($school_id, $profile, $location = null, $generateUserFlag = true)
    {
        $profile['school_id'] = $school_id;
        $userProfile = new UserProfileRepository(null, $profile);
        $keyName = $userProfile->getModel()->getKeyName();
        
        if(!$profile[$keyName] && $generateUserFlag)
        {
            // auto genereae User
            $user = new UserRepository(null, $profile);
            if(!$user->generateUser())
            {
                $this->setErrorMessage($user->error());
                return false;
            }
            // set user ID
            $userProfile->setUserId($user->getModel()->user_id);
        }
        
        // find or create location 
        $userLocation = (new LocationRepository())->findOrCreate($location['village_id'],$location['neighbourhood'], $location['hamlet'], $location['address']);
        $userProfile->setLocationId($userLocation->getModel()->location_id);
        
        // create Profile
        if(!$userProfile->save())
        {
            $this->setErrorMessage($userProfile->error());
            DB::rollback();
            return false;
        }
        
        $userProfile->getModel()['location'] = $userLocation;
        
        return $userProfile->getModel();
    }
    
    /**
     * 
     * @return type
     */
    public function getErrorMessage()
    {
        return $this->error;
    }
    
    /**
     * 
     * @param type $message
     * @return type
     */
    public function setErrorMessage($message)
    {
        return $this->error = $message;
    }
}