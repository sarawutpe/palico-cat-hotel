<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueUser implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected $user;

    public function __construct($user = '')
    {
        $this->user = $user;
    }

    public function passes($attribute, $value)
    {
        // Check if the 'value' value exists in any of the three tables
        $member_count = DB::table('members')->where('member_user', $value)->where('member_user', '!=', $this->user)->count();
        $employee_count = DB::table('employees')->where('employee_user', $value)->where('employee_user', '!=', $this->user)->count();

        return $member_count === 0 && $employee_count === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'ผู้ใช้นี้มีอยู่แล้ว';
    }
}
