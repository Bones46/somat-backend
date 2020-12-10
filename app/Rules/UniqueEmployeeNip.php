<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\employees;

class UniqueEmployeeNip implements Rule
{
    protected $customId,$employeeId,$matches;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($customId = null)
    {
        $this->customId = $customId;
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
        $this->matches = employees::whereSchoolId(request('school_id'))
        ->whereNip(request('nip'))
        ->count();

        if ($this->customId === null) {
            return $this->matches === 0;
        } 

        $this->employeeId = employees::whereSchoolId(request('school_id'))
        ->whereNip(request('nip'))
        ->value('employee_id');
        
        if ($this->matches > 0) {


            if ($this->employeeId == $this->customId) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'school_id + nip has been taken => '.$this->employeeId.' - '.$this->customId.' - '.$this->matches;
    }
}
