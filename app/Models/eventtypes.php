<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $event_type_id
 * @property string $name
 * @property string $route_name
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Event[] $events
 */
class eventtypes extends Model
{
    /** 
     *  The constant name of default timestamps field laravel
     * 
     *  @var string
    */

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    /** 
     *  The table associated with the model.
     * 
     *  @var string
    */
    protected $table = 'event_types';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'event_type_id';

    /**
     * @var array
     */
    protected $fillable = ['name', 'route_name', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event', 'event_type_id', 'event_type_id');
    }
}
