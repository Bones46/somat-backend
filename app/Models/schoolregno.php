<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $school_reg_no_id
 * @property int $school_id
 * @property string $prefix
 * @property string $suffix
 * @property int $start_sequence
 * @property int $digit_sequence
 * @property string $delimiter
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property School $school
 */
class schoolregno extends Model
{
    /** 
     *  The constant name of default timestamps field laravel
     * 
     *  @var string
    */

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'update_date';

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'school_reg_no';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'school_reg_no_id';

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'prefix', 'suffix', 'start_sequence', 'digit_sequence', 'delimiter', 'created_by', 'created_date', 'update_by', 'update_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'school_id');
    }
}
