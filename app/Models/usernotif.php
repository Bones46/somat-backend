<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $user_notif_id
 * @property integer $user_id
 * @property string $unread
 * @property integer $last_notif_id
 * @property int $total
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property UserProfile $userProfile
 */
class usernotif extends Model
{
    /** 
     *  The constant name of default timestamps field laravel
     * 
     *  @var string
    */

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_notif';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'user_notif_id';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'unread', 'last_notif_id', 'total', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userProfile()
    {
        return $this->belongsTo('App\Models\UserProfile', 'user_id', 'user_profile_id');
    }
}
