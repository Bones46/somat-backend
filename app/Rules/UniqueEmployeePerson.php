<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\employees;

class UniqueEmployeePerson implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $matches = employees::whereSchoolId(request('school_id'))
        ->wherePersonId(request('person_id'))
        ->whereEffectiveStartDate(request('effective_start_date'))
        ->count();

        return $matches === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'school_id + person_id + effective_start_date has been taken';
    }
}
