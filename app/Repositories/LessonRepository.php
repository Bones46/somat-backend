<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class LessonRepository  extends Repository //implements libraryContract
{ 
    const EXTCUR_LESSON = 'ekstakulikuler';
    const LESSON        = 'C';
    /**
     * constructor
     * 
     * @param type $request Illuminate\Http\Request;
     */
    public function __construct($id = null ,$data = null) 
    {
        parent::__construct( 'lesson', $id, $data);
    }
    
    /**
     * retrieve All lesson 
     * 
     * @return array
     */
    public  function getLesson($school_id, $lessonType)
    {
        if($lessonType ==LessonRepository::LESSON)
            return $this->model->getLesson($school_id);
        
        return $this->model->getExtCur($school_id);
    }
    
    /**
     * 
     * 
     * @param int $school_id
     * @param string $lessonType
     */
    public function getLessonBySchool($school_id, $lessonType)
    {
        return $this->getLesson($school_id, $lessonType)->get();
    }
    
    /**
     * 
     * @param int $school_id
     * @param string  $lessonType
     */
    function getActiveLessonBySchool($school_id, $lessonType)
    {
        return $this->getLesson($school_id, $lessonType)->active()->get();
    }
    
    /**
     * save  to storage
     * 
     * @param type array||object 
     * @return mixed
     */
    public  function save($school_id, $data)
    {  
        $result = array();
        
        DB::beginTransaction();
        $data['school_id'] = $school_id;
        $this->data =$data;
        if(!$this->store())
        {
            DB::rollBack();
            return false;
        }
        
        if($data['lesson']['group_flag'] === 'Y')
        {
            foreach ($data['group'] as $key => $item)
            {
                $item['school_id']          = $this->model->school_id;
                $item['parent_lesson_id']   = $this->model->lesson_id;
                $item['lesson_category_id'] = $this->model->lesson_category_id;

                $child = new LessonRepository(null, $item);
                if(!$child->store())
                {
                    $this->setErrorMessage($child->error());
                    DB::rollBack();
                    return false;
                }
                $result[$key] = $child->getModel();
            }
        }
         
        DB::commit();
            
        return  $data['group_flag'] === 'Y' ?$result : $lesson;
    }
}
