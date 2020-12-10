<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $notification_id
 * @property integer $from_user_id
 * @property integer $to_user_id
 * @property int $event_id
 * @property string $read_flag
 * @property integer $document_id
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Event $event
 * @property UserProfile $userProfile
 * @property UserProfile $userProfile
 */
class notification extends Model
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
    protected $table = 'notification';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'notification_id';

    /**
     * @var array
     */
    protected $fillable = ['from_user_id', 'to_user_id', 'event_id', 'read_flag', 'document_id', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id', 'event_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo('App\Models\UserProfile', 'from_user_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toUser()
    {
        return $this->belongsTo('App\Models\UserProfile', 'to_user_id', 'user_profile_id');
    }
}
