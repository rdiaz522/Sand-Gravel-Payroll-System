<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashDeduction extends Model
{
    use HasFactory;

    protected $table = 'cash_deductions';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'employee_id',
       'cash_deduction',
       'cash_advance_id',
       'new_cash_advance_balance',
       'cash_deduction_date',
   ];

   public $timestamps = true;

   public function employees()
   {
       return $this->hasMany(Employees::class);
   }
}
