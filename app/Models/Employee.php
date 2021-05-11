<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = ['employee_id', 'grade_id', 'name', 'mobile_number', 'address'];

    public function grade () {
        return $this->belongsTo(Grade::class, 'grade_id', 'id');
    }

    public function account () {
        return $this->hasOne(EmployeeAccount::class, 'employee_id', 'id');
    }
}
