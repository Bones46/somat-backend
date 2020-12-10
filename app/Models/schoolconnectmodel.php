<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

/**
 *
 * 
 */
class SchoolConnectModel extends Model
{
    /** 
     *  The constant name of default timestamps field laravel
     * 
     *  @var string
    */
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
  
    /**
     * 
     * @param type $query
     * @param type $school_id
     * @return type
     */
    public function scopeGetBySchool($query, $school_id)
    {  
        return $query->where('school_id',$school_id);     
        
        Hash::make(' ');
    }
    
    
    
    /**
     * 
     * @param type $query
     * @return type
     */
    public function scopeActive($query)
    {
        return $query->where('active_flag', 'Y');
    }
}
