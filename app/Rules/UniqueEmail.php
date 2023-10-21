<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueEmail implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    protected $email;

    public function __construct($email = '')
    {
        $this->email = $email;
    }

    public function passes($attribute, $value)
    {
        // Check if the 'value' value exists in any of the three tables
        $member_count = DB::table('members')->where('member_email', $value)->where('member_email', '!=', $this->email)->count();
        $employee_count = DB::table('employees')->where('employee_email', $value)->where('employee_email', '!=', $this->email)->count();
        return $member_count === 0 && $employee_count === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'อีเมลผู้ใช้งานนี้มีอยู่แล้ว';
    }
}
