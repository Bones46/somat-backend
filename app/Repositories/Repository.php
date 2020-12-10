<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use Illuminate\Support\Facades\File;
use Carbon;

class Repository
{ 
    const _NAMESPACE ='App\\Models\\';

    protected $data = array();
    protected $model;
    protected $update = FALSE;
    protected $modelName;
    protected $nameSpace;
    protected $keyName;
    protected $id;
    
    public static $error;
    /**
     * constructor
     * 
     * @param type array @data 
     * @param modelName string ,the name of model to be be save in storage
     * @param tableSpace string , directory stores model
     */
    public function __construct($modelName,  $id = null, $data=null, $nameSpace = null)
    {
        $this->modelName =$modelName;
        $this->data = $data;
        if(is_null($nameSpace))
            $this->nameSpace = self::_NAMESPACE;  
        
        $this->model = $this->getModel();
        $this->id = $id;
    }

    /*
     * Store a newly created resource in storage.
     * 
     * return Booelan 
     */
    public function create()
    {
        $this->collect();
        $this->model->created_by = Auth::User()->user_id;
        
        try{
            $this->getModel()->save();
        } catch (\Illuminate\Database\QueryException $exception) {
            // check get the details of the error using `errorInfo`:
            self::$error = $exception->errorInfo;
            return FALSE;
        }            
                
        return true; 
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @return Boolean
     */
    public function update()
    {
        if(!$this->findByID())
            return FALSE;
        
        $this->collect();
        
//        if(!$this->update)
//        {
//            $this->error = 'No Data To Be Updated';
//            return FALSE;
//        }
//        
        try{
            if($this->update)
                $this->getModel()->update();
        } catch (\Illuminate\Database\QueryException $exception) {
            // check get the details of the error using `errorInfo`:
            self::$error = $exception->errorInfo;
            return FALSE;
        }
        
        return  TRUE; 
    }
    
    /**
     * delete data
     * 
     * @return boolean
     */
    public function deleteById()
    {      
        if(!$this->findByID())
            return FALSE;
        
      return $this->destroy();
    }
    
    /**
     * delete source 
     * 
     * @return boolean
     */
    public function destroy()
    {
        try
        {
            $this->getModel()->delete();
        } catch (\Illuminate\Database\QueryException $exception) {
            self::$error = $exception->errorInfo;
            return FALSE;
        }
        
        return  TRUE; 
    }
    
    /**
     * 
     * save resource in storage
     * 
     * @return boolean
     */
    
    public function store()
    {
        if(is_null($this->data))
        {
            self::$error = 'no data to be save';
            return false;
        }
        
        //if(array_key_exists($keyName,$this->data))
        if(!empty($this->getId()) || !is_null($this->getId()))
            return $this->update();
        
        return $this->create();
    }
    
    /**
     * 
     * assignment data to model 
     * 
     */
    protected function collect()
    {
       foreach($this->model->getFillable() as $key)
       {
            //$this->model->$key = null;       
            if(array_key_exists($key, $this->data) && $this->data[$key] !== $this->model->$key) {
                $this->model->$key = $this->data[$key];
                
            if(!$this->update)
                $this->update = TRUE;
            }
            
            if($key === 'updated_by')
                $this->model->updated_by = Auth::User()->user_id;
            
        }
    }
    
    /**
     * get the name of primary key model
     * 
     * @return string
     */
    protected  function getKeyName()
    {
        
        if(!is_null($this->keyName))
            return $this->model->getKeyName();
        
        if(!$modelName = $this->getModelName())
            return false;
        
        $this->model = new  $modelName();
        
        if (is_null($this->keyName = $this->model->getKeyName())) {
            self::$error = 'No primary key defined on model.';
            return false;
        }
        
        return $this->keyName;
    }
    
    /**
     * find by primary key of model
     * 
     * return mixed
     */
    public function findByID()
    {         
        $model = $this->model->find($this->getId());
        if(!$model )
        {
            self::$error = 'No Data Found';
            return FALSE;
        }
        
        return $this->model =$model ;
    }

    /**
     * full path of model  
     * 
     * @return string
     */
    protected function getModelName()
    {
        $modelName =$this->nameSpace. $this->modelName;
        if(!class_exists($modelName ))
        {
            self::$error = 'No class Found';
            return False;
        }
        
        return $modelName;
    }
    
    /**
     * get the id of model
     * 
     * @return mixed 
     */
    protected function getId()
    {
        if(!is_null($this->id))
            return $this->id;
        
        if (!is_null($this->data) && array_key_exists($this->model->getKeyName(), $this->data))
            return  $this->data[$this->model->getKeyName()];
        
        return null;
        
    }
    
    /**
     * 
     * @param string $message
     */
    public static function setErrorMessage($message)
    {
        self::$error = $message;
    }

    /**
     * message error process 
     */
    public static function error()
    {
        return self::$error;
    }
    
    /**
     * 
     * @return eloquent
     */
    public function getModel()
    {
        if(is_null($this->model))
        {
            if(!$modelName = $this->getModelName())
                return false;
            
           return  new $modelName();
        }
        
        return $this->model;
    }
    
     /**
     * 
     * @param type $school_id
     * @param type $wit
     * @return type
     */
    public function getQueryBySchool($school_id, $active=false)
    {
         $query = $this->model->getBySchool($school_id);
         if($active)
             $query->active();
         
         return $query;
    }
    
    /**
     * 
     * @param type $school_id
     * @param type $wit
     * @return type
     */
    public function getBySchool($school_id, $active=false)
    {
         return $this->getQueryBySchool($school_id, $active)->get();
    }
    
    /**
     * 
     * @param type $school_id
     * @param type $with
     * @return type
     */
    public function getBySchoolWith($school_id, $with, $active = false)
    {
         return $this->getQueryBySchool($school_id, $active)
                     ->with($with)
                     ->get();
    }
    
    /**
     * 
     * @param type $school_id
     * @param type $id
     * @param array|string $with relation model
     * @return type
     */
    public function getByIdWith($school_id, $id, $with)
    {
        return $this->getQueryBySchool($school_id)
                    ->where($this->getKeyName(), '=', $id)
                    ->with($with)
                    ->first();
    }
    
    /**
     * 
     * @return type
     */
    public function getActive()
    {
        return $this->model->Active()->get();
    }
    
    /**
     * check value array is empty
     * 
     * @param array $data
     * @param type $stack
     * @return boolean
     */
    public function isEmpty(array $data, $stack)
    {
        if(!array_key_exists($stack, $data))
            return false;
        
        return empty($data[$stack]) || !is_null($data[$stack]);
    }
    
    /**
     * 
     * @param Request $request
     * @param type $file
     */
    public function upload(Request $request, $file)
    {
        //path directory upload image
        $path = storage_path('app/public/images');
        
        // dimention
        $dimensions = ['300'];
        
        // if folder doesnt exist 
        if (!File::isDirectory($path)) {
            //create new folder
            File::makeDirectory($path);
        }
        
        //create new filename of image upload 
        $fileName = Carbon::now()->timestamp . '_' . uniqid();// . '.' . $file->getClientOriginalExtension();
        Image::make($file)->save($this->path . '/' . $fileName.'_origin'. '.' . $file->getClientOriginalExtension());
        
        foreach ($dimensions as $row) {
            $canvas = Image::canvas($row, $row);
            $resizeImage  = Image::make($file)->resize($row, $row, function($constraint) {
                $constraint->aspectRatio();
            });
			
            if (!File::isDirectory($this->path . '/' . $row)) {
                //MAKA BUAT FOLDER DENGAN NAMA DIMENSI
                File::makeDirectory($this->path . '/' . $row);
            }
        	
            //MEMASUKAN IMAGE YANG TELAH DIRESIZE KE DALAM CANVAS
            $canvas->insert($resizeImage, 'center');
            //SIMPAN IMAGE KE DALAM MASING-MASING FOLDER (DIMENSI)
            $canvas->save($this->path . '/' . $row . '/' . $fileName);
        }
    }
}
