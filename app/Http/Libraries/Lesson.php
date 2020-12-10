<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\Auth;
use App\Lesson as mLesson;
use App\Http\Libraries\Permission;
use Illuminate\Support\Facades\DB;

class Lesson  extends library //implements libraryContract
{ 
    protected $group;
    protected $isGroup;
    
    const EXTCUR_LESSON = 'ekstakulikuler';
    const LESSON        = 'pelajaran';
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id ,$data = null) 
    {
        parent::__construct($id, $data, 'Lesson');
    }
    
    /**
     * find lesson by id
     * 
     * @param int $lessonId, lesson id or parent id
     * @return mixed
     */
    public static function findById($lessonId)
    {
        $lesson = mLesson::findByID($lessonId)->get();
        if(!$lesson)
        {
            static::$error = 'No Data Found';
            return false;
        }
            
         return $lesson;
    }
    
    /**
     * retrieve All lesson 
     * 
     * @return array
     */
    public static function getLesson($lessonType)
    {
        /**
         * TO DO filter by school 
         */
        if($lessonType ==Lesson::LESSON)
            return mLesson::getLesson()->active()->get();
        
        return mLesson::getExtCur()->active()->get();
    }
    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public static function save($data)
    {
        DB::beginTransaction();
        
        $array = array();
        $lesson  = new Lesson(null, $data['lesson']);
        if(!$lesson->simpan())
        {
            DB::rollBack();
            return false;
        }
        
        if($data['lesson']['group_flag'] === 'Y')
        {
            foreach ($data['group'] as $key => $item)
            {
                $item['parent_lesson_id'] = $lesson->getModel()->lesson_id;
                $item['lesson_category_id'] = $lesson->getModel()->lesson_category_id;

                $child = new Lesson(null, $item);
                if(!$child->simpan())
                {
                    DB::rollBack();
                    return false;
                }
                $array[$key] = $child->getModel();
            }
        }
         
        DB::commit();
            
        return  $data['group_flag'] === 'Y' ?$array : $lesson;
    }
}
