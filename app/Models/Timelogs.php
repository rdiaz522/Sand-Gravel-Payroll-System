<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timelogs extends Model
{
    use HasFactory;

    protected $table = 'employee_timelogs';
    
    public $timestamps = true;

    protected $fillable = [
        'employee_id',
        'position_id',
        'daily_rate',
        'time_in',
        'time_out',
        'break_time',
        'total_hours',
        'log_date' 
    ];
}
