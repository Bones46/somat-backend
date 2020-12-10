<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\Auth;

class Library
{
    const _NAMESPACE ='App\\';

    protected $data;
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
     * @param type object @data
     * @param modelName string ,the name of model to be be save in storage
     * @param tableSpace string , directory stores model
     */
    public function __construct($id = null, $data, $modelName, $nameSpace = null)
    {
        $this->id = $id;
        $this->data = $data;
        $this->modelName =$modelName;
        if(is_null($nameSpace) || is_null( $this->nameSpace))
            $this->nameSpace = self::_NAMESPACE;
    }

    /*
     * Store a newly created resource in storage.
     *
     * return Booelan
     */
    public function store()
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
        if(!$this->findModelByID())
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
        if(!$this->getKeyName())
            return false;

        if(!$this->findModelByID())
            return FALSE;

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

    public function simpan()
    {
        if(!$keyName = $this->getKeyName())
            return false;

        //if(array_key_exists($keyName,$this->data))
        if(!empty($this->data[$keyName]) || !is_null($this->data[$keyName]))
            return $this->update();

        return $this->store();
    }

    /**
     *
     * assignment data to model
     *
     */
    protected function collect()
    {
       foreach($this->getModel()->getFillable() as $key)
       {

            $this->model->$key = null;
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
     */
    public function findModelByID()
    {
        $modelName= $this->getModelName();
        $keyName = $this->getKeyName();

        $id = is_null($this->id) ? $this->data[$keyName] : $this->id;
        $model =$modelName::find($id);
        if(!$model )
        {
            self::$error = 'No Data Found';
            return FALSE;
        }

        $this->model =$model ;
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
        return $this->model;
    }
}
