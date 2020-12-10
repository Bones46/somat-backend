<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $created_at
 * @property string $updated_att
 */
class Role extends Model
{
    
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_roles';
    
     /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * @var array
     */
    protected $fillable = ['name', 'slug','school_id', 'created_by', 'updated_by'];
    

     /**
     * A role belongs to many users.
     *
     * @return BelongsToMany
     */
    public function user() : BelongsToMany
    {
        return $this->belongsToMany('App\Models\User', 'sc_role_users', 'user_id', 'role_id');
    }
    
    /**
     * A role belongs to many Menus.
     *
     * @return BelongsToMany
     */
    public function menus() : BelongsToMany
    {
        return $this->belongsToMany('App\Models\Menu', 'sc_role_menu', 'role_id', 'menu_id');
    }
    
    /**
     * A role belongs to many permission.
     *
     * @return BelongsToMany
     */
    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany('App\Models\Permission', 'sc_role_permissions', 'role_id', 'permission_id');
    }
    
    /**
     * 
     */
    public static function scopeFindById($query, $id)
    {
        return $query->where('id', $id)->with('permissions');
    }
    
     /**
     * Check user has permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function can(string $permission) : bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot(string $permission) : bool
    {
        return !$this->can($permission);
    }
    
    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->Permissions()->detach();

            //$model->permissions()->detach();
        });
    }
}
