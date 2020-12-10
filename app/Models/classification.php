<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $classification_id
 * @property int $school_id
 * @property string $classification_name
 * @property string $description
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 */
class classification extends SchoolConnectModel
{    
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_classification';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'classification_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'classification_name', 'description', 'created_by', 'created_date', 'update_by', 'update_date'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employee()
    {
        return $this->hasMany('App\Models\EmployeeClassification', 'classification_id', 'classification_id');
    }

    public function employees()
    {
        return $this->hasManyThrough('App\Models\Employees',
                                'App\Models\EmployeeClassification',
                                'classification_id',
                                'employee_id',
                                'classification_id',
                            'employee_id');
    }
}
