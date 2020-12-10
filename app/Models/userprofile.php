<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $user_profile_id
 * @property int $user_id
 * @property integer $location_id
 * @property integer $nik
 * @property integer $kk
 * @property string $npwp
 * @property string $full_name
 * @property string $mobile_number
 * @property string $phone_no
 * @property string $place_of_birth
 * @property string $date_of_birth
 * @property string $gender
 * @property string $religion
 * @property string $job
 * @property string $blood_type
 * @property string $salary
 * @property string $graduated
 * @property string $profile_image
 * @property string $effective_start_date
 * @property string $effective_end_date
 * @property int $created_by
 * @property string $created_date
 * @property int $update_by
 * @property string $update_date
 * @property Location $location
 * @property User $user
 * @property LessonTeacher[] $lessonTeachers
 * @property ScheduleClass[] $scheduleClasses
 * @property Notification[] $notifications
 * @property Notification[] $notifications
 * @property School[] $schools
 * @property UserMapping[] $userMappings
 * @property UserMapping[] $userMappings
 * @property UserNotif $userNotif
 * @property Student[] $students
 * @property Employee[] $employees
 * @property Class[] $classes
 */
class userprofile extends Model
{
    
    protected $appends =array('location');
    /**
     *  The constant name of default timestamps field laravel
     *
     *  @var string
    */

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_profile_id';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'location_id', 'nik', 'kk', 'npwp', 'first_name','last_name', 'mobile_number',
                'phone_no', 'place_of_birth', 'date_of_birth', 'gender', 'religion', 'job', 'blood_type',
                'salary', 'graduated', 'profile_image', 'effective_start_date', 'effective_end_date',
                'status', 'citizenship', 'country', 'created_by', 'created_date', 'updated_by', 'updated_date'];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('schoolconnect.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('schoolconnect.database.user_profile_table'));

        parent::__construct($attributes);
    }

    public function family()
    {
        return $this->belongsToMany('App\Models\UserProfile','sc_user_map','person_id','related_person_id');
        //return $this->hasManyThrough('App\Models\UserProfile','App\Models\Usermapping','join_person_id','user_profile_id','user_profile_id','person_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo('App\Models\Locations', 'location_id', 'location_id');
    }
    
    /**
     * 
     */
    public function getLocationAttribute()
    {
        return $this->location()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'user_id', 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessonTeachers()
    {
        return $this->hasMany('App\Models\LessonTeacher', 'teacher_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scheduleClasses()
    {
        return $this->hasMany('App\Models\ScheduleClass', 'teacher_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fromNotifications()
    {
        return $this->hasMany('App\Models\Notification', 'from_user_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toNotifications()
    {
        return $this->hasMany('App\Models\Notification', 'to_user_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function personIdMappings()
    {
        return $this->hasMany('App\Models\UserMapping', 'join_person_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function joinIdMappings()
    {
        return $this->hasMany('App\Models\UserMapping', 'person_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userNotif()
    {
        return $this->hasOne('App\Models\UserNotif', 'user_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany('App\Models\Students', 'person_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees()
    {
        return $this->hasMany('App\Models\Employees', 'person_id', 'user_profile_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classes()
    {
        return $this->hasMany('App\Models\Classes', 'teacher_id', 'user_profile_id');
    }
}
