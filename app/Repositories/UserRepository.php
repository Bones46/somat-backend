<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;

class UserRepository  extends Repository //implements libraryContract
{ 
    protected $user;
    const substring = 6;
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id =null ,$data = null) 
    {
        parent::__construct( 'User', $id, $data);
    }
    
    /**
     * retrieve permission group 
     * if administrator get all permission group, besides taking data based on the user login
     * 
     * @return array
     */
    public static function getUser()
    {
        return User::All();
    }
    
    /**
     * save to storage
     * 
     * @return boolean
     */
    public function save()
    {
        $this->data['password'] = bcrypt($this->data['password']);
        return $this->create();
    }
    
    /**
     * 
     * @return type
     */
    public function updatePassword()
    {
        $this->data['password'] = bcrypt($this->data['password']);
        return $this->update();
    }
    
    /**
     * TODO 
     * - Send Email 
     * generate a user account when first registering as a member
     * 
     * @param type array
     * @return Boolean
     */
    public function generateUser()
    {
        if(is_null($this->data))
        {
            $this->setErrorMessage('no data to be processes');
            return false;
        }
        
        if($this->isEmail($this->data['email']))
        {
            $this->setErrorMessage('email has been already used');
            return false;
        }
        
        $this->data['username'] = $this->generateUsername();        
        $this->data['password'] = $this->getDefaultPassword();
        
        return $this->save();
    }
    
    /**
     * 
     */
    public function getDefaultPassword()
    {
       return Carbon::parse($this->data['date_of_birth'])->format('dmY');
    }
    
    /**
     * generate auto username for user
     * 
     * @return string
     */
    public function generateUsername()
    {
        // get username combine by username and birthday
        $username = $this->generateUsernameByName();
        if (!is_null($username)) {
            return $username;
        }

        // get username by user email
       $username = $this->generateUsernameByEmail();
       if (!is_null($username)) {
            return $username;
        }

        return null;          
    }
    
    
    /**
     * get username by combine full name and birthday user 
     * @param $count int  the substring starting from  until the end of the string will be returned
     * 
     * return string
     */
    protected function generateUsernameByName($count = UserRepository::substring)
    {
        $fullname= $this->data['first_name'].''.$this->data['last_name'];
        $username = substr(str_replace(' ', '', $fullname),0,$count).Carbon::parse($this->data['date_of_birth'])->format('dy');
        if($this->isUsername($username)&& ($count - UserRepository::substring) != 3)
           $username = $this->generateUsernameByName ($count+1);
        
        return $username;
    }
    
    /**
     * generate username use email user
     * 
     * @return mixed
     */
    protected function generateUsernameByEmail()
    {
        if (is_null('email')) {
            return null;
        }

        $username = substr($this->data['email'], 0, strpos($this->data['email'], '@')-1);
        if (!$this->isUsername($username)) {
            return $username;
        }

        return null;
        
    }
    
    /**
     * get username by fullname of user and combine with suffle date of birthday
     * 
     * @return string
     */
    public function suffleUsername()
    {
        $fullname= $this->data['first_name'].''.$this->user['last_name'];
        $username = substr(str_replace(' ', '', $fullname),0,6). str_shuffle(Carbon::parse($this->user['date_of_birth'])->format('dy'));
        if ($this->isUsername($username)) {
            $username = $this->suffleUsername();
        }

        return $username;
    }
    
    /**
     * check the username has already used another userc
     * 
     * @param string $username
     * @return boolean
     */
    public function isUsername($username)
    {
        return $this->getByUsername($username)->count() > 0;
    }
    
     
    /**
     * check the Email has already used another userc
     * 
     * @param string $email
     * @return boolean
     */
    public function isEmail($email)
    {
        return $this->getByEmail($email)->count() > 0;
    }
    
    /**
     * get user by username
     * 
     * @param type $username string
     * 
     * @return object
     */
    public function getByUsername($username)
    {
        return $this->getModel()->getByUsername($username); 
    }
    
    /**
     * 
     * @param type $email
     * @return type
     */
    public function getByEmail($email)
    {
        return $this->getModel()->getByEmail($email);
    }
}
