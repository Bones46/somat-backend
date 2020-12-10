<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $group_code_id
 * @property string $group_code_name
 * @property string $description
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property MappingCode[] $mappingCodes
 */
class lookup extends Model
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
    protected $table = 'sc_lookup';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'lookup_id';

    /**
     * @var array
     */
    protected $fillable = ['lookup_name', 'description', 'effective_start_date', 'effective_end_date', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lookupCode()
    {
        return $this->hasMany('App\Models\lookupCode', 'lookup_name', 'lookup_name');
    }

    public static function scopeActive($query)
    {
        return $query->whereRaw('(now() > effective_start_date and now() < effective_end_date)'); //and effective_end_date = null)');
    }
}
