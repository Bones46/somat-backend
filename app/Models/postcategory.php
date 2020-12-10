<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $post_category_id
 * @property int $school_id
 * @property int $parent_cat_id
 * @property string $name
 * @property string $active_flag
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property PostCategory $postCategory
 * @property School $school
 * @property Post[] $posts
 */
class postcategory extends Model
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
    protected $table = 'post_category';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'post_category_id';

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'parent_category_id', 'name', 'active_flag', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School', 'school_id', 'school_id');
    } 

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function childCategory()
    {
        return $this->belongsTo('App\Models\PostCategory', 'parent_cat_id', 'post_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parentCategories()
    {
        return $this->hasMany('App\Models\PostCategory', 'parent_cat_id', 'post_category_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany('App\Models\Post', 'post_category_id', 'post_category_id');
    }
}
