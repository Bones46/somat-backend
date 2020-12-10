<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $event_id
 * @property int $event_type_id
 * @property string $event_date
 * @property string $message
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property EventType $eventType
 * @property Notification[] $notifications
 */
class event extends Model
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
    protected $table = 'event';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'event_id';

    /**
     * @var array
     */
    protected $fillable = ['event_type_id', 'event_date', 'message', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function eventType()
    {
        return $this->belongsTo('App\Models\EventTypes', 'event_type_id', 'event_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications()
    {
        return $this->hasMany('App\Models\Notification', 'event_id', 'event_id');
    }
}
