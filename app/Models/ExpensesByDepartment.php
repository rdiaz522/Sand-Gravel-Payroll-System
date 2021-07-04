<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpensesByDepartment extends Model
{
    use HasFactory;

    protected $table = 'expenses_by_departments';

    protected $fillable = [
        'department_id',
        'description',
        'amount',
        'cash_from'
    ];

    public $timestamps = true;
}
