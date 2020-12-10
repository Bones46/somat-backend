<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id_districts
 * @property integer $id_province
 * @property string $districts_name
 * @property Province $province
 * @property Subdistrict[] $subdistricts
 */
class districts extends Model
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_districts';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'district_id';

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
    protected $fillable = ['province_id', 'district_name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo('App\Models\Province', 'province_id', 'province_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subdistricts()
    {
        return $this->hasMany('App\Models\Subdistricts', 'district_id', 'district_id');
    }
}
