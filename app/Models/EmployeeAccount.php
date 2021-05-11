<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAccount extends Model
{
    protected $table = 'employee_accounts';

    protected $fillable = ['employee_id', 'account_type', 'account_name', 'account_number', 'bank_name', 'branch_name', 'current_balance'];
}
