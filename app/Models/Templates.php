<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $template_id
 * @property string $name
 * @property string $description
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $updated_date
 * @property ScPermission[] $scPermissions
 */
class Templates extends Model
{
    
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_templates';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'template_id';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'created_by', 'updated_by',];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Permissions() : HasMany
    {
        return $this->hasMany('App\Models\Permission', 'template_id', 'template_id');
    }
    
    /**
     * @param int id, the primary key of template
     * 
     * return mixed
     */
    public static function scopeFindByid($query, $id)
    {
        return $query->where('template_id', $id)->with('Permissions');
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
            $model->Permissions()->delete();
        });
    }
    
}
