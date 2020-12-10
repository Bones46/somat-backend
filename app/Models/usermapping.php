<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $user_mapping_id
 * @property integer $person_id
 * @property integer $join_person_id
 * @property string $mapping_code_name
 * @property string $primary_flag
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property UserProfile $userProfile
 * @property UserProfile $userProfile
 */
class usermapping extends Model
{
    /** 
     *  The constant name of default timestamps field laravel
     * 
     *  @var string
    */

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'update_date';
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_mapping';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'user_mapping_id';

    /**
     * @var array
     */
    protected $fillable = ['person_id', 'join_person_id', 'mapping_code_name', 'primary_flag', 'created_by', 'created_date', 'update_by', 'update_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function joinId()
    {
        return $this->belongsTo('App\Models\UserProfile', 'join_person_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function personId()
    {
        return $this->belongsTo('App\Models\UserProfile', 'person_id', 'user_profile_id');
    }
}
