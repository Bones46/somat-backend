<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $post_id
 * @property int $post_category_id
 * @property int $school_id
 * @property string $title
 * @property string $content
 * @property string $image
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property string $priority_start_date
 * @property string $priority_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property PostCategory $postCategory
 * @property School $school
 */
class post extends Model
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
    protected $table = 'post';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'post_id';

    /**
     * Indicates if the IDs are auto-incrementing.
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['post_category_id', 'school_id', 'title', 'content', 'image', 'effective_start_date', 'effective_end_date', 'priority_start_date', 'priority_end_date', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function postCategory()
    {
        return $this->belongsTo('App\Models\PostCategory', 'post_category_id', 'post_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'school_id');
    }
}
