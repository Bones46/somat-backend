<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $village_id
 * @property integer $subdistricts_id
 * @property string $village_name
 * @property string $postal_code
 * @property Subdistrict $subdistrict
 * @property Location[] $locations
 */
class village extends Model
{
    public $timestamps = false;
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_village';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'village_id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['subdistricts_id', 'village_name', 'postal_code'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subdistrict()
    {
        return $this->belongsTo('App\Models\Subdistricts', 'subdistrict_id', 'subdistrict_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany('App\Models\Locations', 'village_id', 'village_id');
    }
}
