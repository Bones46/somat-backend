<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class MultipleUnique implements Rule
{
    protected $colArr=[];
    protected $table;
    protected $primaryKey;
    protected $primaryValue;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, array $colArr, $primaryValue=null, $primaryKey = 'id')
    {
        $this->table = $table;
        $this->colArr = $colArr;
        $this->primaryKey = $primaryKey;
        $this->primaryValue = $primaryValue;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $colValue = explode(".", $attribute);
        $query = DB::table($this->table)->where(end($colValue), $value);
        foreach ($this->colArr as $col => $val) {
            $query->where($col, $val);
        }
        
        if ($this->primaryValue) {
            $query->where($this->primaryKey, '!=', $this->primaryValue);
        }

        return ($query->count())>0 ? false : true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique');
    }
}
