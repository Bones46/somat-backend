<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $permission_group_id
 * @property int $permission_id
 * @property string $name
 * @property string $description
 * @property ScPermission $scPermission
 */
class PermissionGroup extends Model
{
    
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_permission_groups';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'permission_group_id';

    /**
     * @var array
     */
    protected $fillable = ['permission_id', 'name', 'description', 'created_by','updated_by'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Permission()
    {
        return $this->belongsToMany('App\Permission', 'sc_permission_maps','permission_group_id',  'permission_id');
    }
    
     /**
     * A role belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function Roles() : BelongsToMany
    {
        return $this->belongsToMany('App\Role','sc_role_permissions' , 'permission_group_id', 'role_id');
    }
}
