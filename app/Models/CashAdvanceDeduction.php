<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvanceDeduction extends Model
{
    use HasFactory;

    protected $table = 'cash_advance_deductions';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'employee_id',
        'cash_advance',
        'cash_advance_description',
        'cash_advance_date',
    ];

    public $timestamps = true;

    public function employees()
    {
        return $this->hasMany(Employees::class);
    }
    
}
