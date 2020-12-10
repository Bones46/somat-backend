<?php

namespace App;

use App\Contracts\ModelInterface as ModelContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Collection;
/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $http_method
 * @property string $http_path
 * @property string $created_at
 * @property string $updated_at
 */
class Permission extends Model
{
    public $errorMsg;
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'sc_permissions';

    /**
     * @var array
     */
    protected $fillable = ['name', 'slug', 'http_method', 'http_path','menu_flag','template_id','updated_by', 'created_by'];
    
    /**
     * @var array
     */
    public static $httpMethods = [
        'GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS', 'HEAD',
    ];
        
     /**
     * Permission belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles() : BelongsToMany
    {
        return $this->belongsToMany('App\Role', 'sc_role_permissions', 'role_id', 'permission_id');
    }
    
    /**
     * Permission many to one template.
     *
     * @return BelongsToMany
     */
    public function template() : HasOne
    {
        return $this->hasOne('App\Templates', 'template_id', 'template_id');
    }
    
    /**
     * get permission by slug name 
     * 
     * @param slug , alisa name of route
     * $@param @query
     */
    public static function scopeGetBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->first();
    }
    
    /**
     * retrive permission by grouping page
     * 
     * @param int @templateId , 
     */
    public static  function scopeGetByTemplate($query, $templateId)
    {
        return $query->where('template_id', $templateId)->get();
    }
    
     /**
     * If request should pass through the current permission.
     *
     * @param Request $request
     *
     * @return bool
     */    
    public function shouldPassThrough(Request $request) : bool
    {
        if (empty($this->http_method) && empty($this->http_path)) {
            return true;
        }

        $method = $this->http_method;

        $matches = array_map(function ($path) use ($method) {
            $path = trim(config('admin.route.prefix'), '/').$path;

            if (Str::contains($path, ':')) {
                list($method, $path) = explode(':', $path);
                $method = explode(',', $method);
            }

            return compact('method', 'path');
        }, explode("\n", $this->http_path));

        foreach ($matches as $match) {
            if ($this->matchRequest($match, $request)) {
                return true;
            }
        }

        return false;
    }

    /**
     * filter \r.
     *
     * @param string $path
     *
     * @return mixed
     */
    public function getHttpPathAttribute($path)
    {
        return str_replace("\r\n", "\n", $path);
    }

    /**
     * If a request match the specific HTTP method and path.
     *
     * @param array   $match
     * @param Request $request
     *
     * @return bool
     */
    protected function matchRequest(array $match, Request $request) : bool
    {
        if (!$request->is(trim($match['path'], '/'))) {
            return false;
        }

        $method = collect($match['method'])->filter()->map(function ($method) {
            return strtoupper($method);
        });

        return $method->isEmpty() || $method->contains($request->method());
    }

    /**
     * @param $method
     */
    public function setHttpMethodAttribute($method)
    {
        if (is_array($method)) {
            $this->attributes['http_method'] = implode(',', $method);
        }
    }

    /**
     * @param $method
     *
     * @return array
     */
    public function getHttpMethodAttribute($method)
    {
        if (is_string($method)) {
            return array_filter(explode(',', $method));
        }

        return $method;
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }

}
