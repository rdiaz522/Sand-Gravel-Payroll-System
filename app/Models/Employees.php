<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    
    protected $table = 'employees';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'middlename',
        'employee_type'
    ];

    public $timestamps = true;

    public function cashAdvance()
    {
        return $this->belongsTo(CashAdvanceDeduction::class,'id','employee_id');
    }

    public function cashDeduction()
    {
        return $this->belongsTo(CashDeduction::class,'id','employee_id');
    }

    public function timeLogs()
    {
        return $this->belongsTo(Timelogs::class,'id','employee_id');
    }

    public function contributions()
    {
        return $this->belongsTo(Contribution::class,'id','employee_id')->select(['sss','pagibig','philhealth']);
    }

}
