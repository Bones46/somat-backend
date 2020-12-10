<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id_subdistricts
 * @property integer $id_districts
 * @property string $subdistricts_name
 * @property District $district
 * @property Village[] $villages
 */
class subdistricts extends Model
{
    public $timestamps = false;
    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_subdistricts';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'subdistrict_id';

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
    protected $fillable = ['districts_id', 'subdistrict_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo('App\Models\Districts', 'district_id', 'district_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function villages()
    {
        return $this->hasMany('App\Models\Village', 'subdistrict_id', 'subdistrict_id');
    }
}
