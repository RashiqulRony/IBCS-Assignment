<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = ['employee_id', 'type', 'transfer_type', 'amount', 'note'];


    public function employee() {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}
