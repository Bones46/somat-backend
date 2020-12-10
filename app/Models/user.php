<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\HasPermissions;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasPermissions;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['username', 'password', 'email', 'forget_password_token', 'inactive_date_token', 'user_role_id', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
     /**
     * A user has and belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany('App\Models\Role', 'sc_role_users', 'user_id', 'role_id');
    }
    
    /**
     * A User has and belongs to many permissions.
     *
     * @return BelongsToMany
     */
    public function permissions() : BelongsToMany
    {
        return $this->belongsToMany('App\Models\Permission', 'sc_user_permissions', 'user_id', 'permission_id');
    }
    
    
     /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userProfile()
    {
        return $this->hasOne('App\Models\UserProfile', 'user_id', 'user_id');
    }
    
     /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('schoolconnect.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('schoolconnect.database.user_table'));

        parent::__construct($attributes);
    }
    
    /**
     * get user by username 
     * 
     * @param string username, user login 
     * 
     */
    public function scopeGetByUsername($query, $username)
    {
        return $query->where('username', $username)->first();
    }
    
    /**
     * get user by username 
     * 
     * @param string username, user login 
     * 
     */
    public function scopeGetByEmail($query, $email)
    {
        return $query->where('username', $email)->first();
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
            $model->permissions()->detach();
        });
    }
}
