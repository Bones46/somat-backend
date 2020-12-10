<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $mapping_code_id
 * @property string $group_code_name
 * @property string $mapping_code_name
 * @property string $description
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property int $sequence
 * @property GroupCode $groupCode
 */

class lookupCode extends Model
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
    protected $table = 'sc_lookup_code';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'lookup_code_id';

    /**
     * @var array
     */
    protected $fillable = ['lookup_name', 'lookup_code_name', 'description', 'effective_start_date', 'effective_end_date', 'sequence', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lookup()
    {
        return $this->belongsTo('App\Models\lookup', 'lookup_name', 'lookup_name');
    }
    
    /**
     * 
     * @param type $query
     * @param type $key
     * @return type
     */
    public static function scopeGetByLookupName($query,$lookupName)
    {
        return $query->where('lookup_name',$lookupName);
    }
    
    /**
     * 
     * @param type $query
     * @return type
     */
    public static function scopeActive($query)
    {
        return $query->whereRaw('(now() > effective_start_date and now() < coalesce(effective_end_date, (NOW() + interval'."'1 hour'".')))'); //and effective_end_date = null)');
    }
}
