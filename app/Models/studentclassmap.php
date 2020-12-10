<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $student_class_map_id
 * @property integer $student_class_id
 * @property integer $student_id
 * @property integer $parent_student_class_id
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property StudentClass $studentClass
 * @property Student $student
 * @property StudentClass $studentClass
 */
class studentclassmap extends Model
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
    protected $table = 'student_class_map';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'student_class_map_id';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';

    /**
     * @var array
     */
    protected $fillable = ['student_class_id', 'student_id', 'parent_student_class_id', 'created_by', 'created_date', 'update_by', 'update_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function studentClass()
    {
        return $this->belongsTo('App\Models\StudentClass', 'student_class_id', 'student_class_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id', 'student_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentStudentClass()
    {
        return $this->belongsTo('App\Models\StudentClass', 'parent_student_class_id', 'student_class_id');
    }
}
