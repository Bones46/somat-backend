<?php

namespace App;

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
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_roles';

    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'created_at', 'updated_at'];

     /**
     * A role belongs to many users.
     *
     * @return BelongsToMany
     */
    public function user() : BelongsToMany
    {
        return $this->belongsToMany('App\User', 'sc_role_users', 'user_id', 'role_id');
    }
    
    /**
     * A role belongs to many Menus.
     *
     * @return BelongsToMany
     */
    public function menus() : BelongsToMany
    {
        return $this->belongsToMany('App\Menu', 'sc_role_menu', 'role_id', 'menu_id');
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
     * 
     */
    public static function scopeFindById($query, $id)
    {
        return $query->where('id', $id)->with('permissions');
    }

     /**
     * A role belongs to many permission.
     *
     * @return BelongsToMany
     */
    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany('App\Permission', 'sc_role_permissions', 'role_id', 'permission_id');
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
            $model->permissionsGroup()->detach();

            //$model->permissions()->detach();
        });
    }
}
