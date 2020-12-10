<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\students;

class UniqueStudentNisn implements Rule
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
        $matches = students::whereSchoolId(request('school_id'))
        ->whereNisn(request('nisn'))
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
        return 'school_id + nisn has been taken';
    }
}
