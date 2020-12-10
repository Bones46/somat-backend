<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $position_id
 * @property string $position_name
 * @property string $description
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Job[] $jobs
 */
class organization extends SchoolConnectModel
{

    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_organizations';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'organization_id';

    /**
     * @var array
     */
    protected $fillable = ['organization_id', 'school_id', 'name', 'description', 'effective_start_date', 'effective_end_date', 'created_by', 'created_date', 'updated_by', 'updated_date'];
}
