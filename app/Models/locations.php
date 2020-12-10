<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $location_id
 * @property integer $village_id
 * @property string $neighbourhood
 * @property string $hamlet
 * @property string $address
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Village $village
 * @property School[] $schools
 * @property UserProfile[] $userProfiles
 */
class locations extends Model
{
    
    protected $village, $subdistrict, $district, $province;

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';
    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'location_id';

    protected $table = 'sc_locations';
    
    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'integer';
    
    /**
     * append variable 
     * 
     * @var array
     */
    protected $appends =array('location_name','village' ,'subdistrict', 'district', 'province');

    /**
     * @var array
     */
    protected $fillable = ['village_id', 'neighbourhood', 'hamlet', 'address','created_by', 'updated_by' ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function villages()
    {
        return $this->belongsTo('App\Models\Village', 'village_id', 'village_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schools()
    {
        return $this->hasMany('App\\Models\School', 'location_id', 'location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userProfiles()
    {
        return $this->hasMany('App\Models\UserProfile', 'location_id', 'location_id');
    }
    
    /**
     * find the exist location or create a new location
     * 
     * @param type $query
     * @param int $villageId
     * @param String $neigbhourhood
     * @param String $hamlet
     * @param String $addrees
     * @return type
     */
    public function scopeFindOrCreate($query, $villageId,$neighbourhood, $hamlet, $address)
    {
        $user_id = Auth::User()->user_id;
        $location = $query->where('village_id', $villageId)
                         ->where('neighbourhood', $neighbourhood)
                         ->where('hamlet', $hamlet)
                         ->where('address', $address)
                         ->first();
        if(!$location)
            $location = static::query()->create(array('village_id'      => $villageId, 
                                                      'neighbourhood'   => $neighbourhood, 
                                                      'hamlet'          => $hamlet, 
                                                      'address'         => $address, 
                                                      'updated_by'      => $user_id, 
                                                      'created_by'      => $user_id)
                   );
       
        return $location;
    }
    
    /**
     * get full name of location
     * 
     * @return string
     */
    public function getLocationNameAttribute()
    {
        $rt = null;
        $rw = null;
        
        if( !is_null($this->getAttribute('hamlet')))
            $rt = ' RT '. $this->getAttribute('hamlet');
        
        if( !is_null($this->getAttribute('neighbourhood')))
            $rw = ' RW '. $this->getAttribute('neighbourhood'). ' ' ;
        
        return  $this->attributes['address'].$rt.$rw.
                $this->getVillageAttribute()->village_name.', '.
                $this->getSubdistrictAttribute()->subdistrict_name.' '.
                $this->getDistrictAttribute()->district_name.' '.
                $this->getProvinceAttribute()->province_name .' - '. $this->getVillageAttribute()->postal_code;
    }
    
    /**
     * get sub village location
     * 
     * @return object
     */
    public function getVillageAttribute()
    {
        if(is_null($this->village))
            $this->village =  $this->villages()->first();
        
        return $this->village;
    }
    
    /**
     * get sub district location
     * 
     * @return object
     */
    public function getSubdistrictAttribute()
    {
        if(is_null($this->subdistrict))
            $this->subdistrict =  $this->getVillageAttribute()->subdistrict()->first();
        
         return $this->subdistrict;
    }
    
    /*
     * get district location 
     * 
     * return object
     */
    public function getDistrictAttribute()
    {
        if(is_null($this->district))
            $this->district =  $this->getSubdistrictAttribute()->district()->first();
        
        return $this->district;
    }
    
    /**
     * get province location
     * 
     * @return object
     */
    public function getProvinceAttribute()
    {
        if(is_null($this->province))
            $this->province =  $this->getDistrictAttribute()->province()->first();
        
        return $this->province;
    }
}
