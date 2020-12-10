<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $lesson_category_id
 * @property string $name
 * @property string $description
 * @property string $active_flag
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Lesson[] $lessons
 * @property Class[] $classes
 */
class lessoncategory extends SchoolConnectModel
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_lesson_category';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'lesson_category_id';

    /**
     * @var array
     */
    protected $fillable = ['school_id' ,'name', 'description', 'active_flag', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany('App\Models\Lesson', 'lesson_category_id', 'lesson_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classes()
    {
        return $this->hasMany('App\Models\Classes', 'category_class_id', 'lesson_category_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\school', 'school_id', 'school_id');
    }
}
