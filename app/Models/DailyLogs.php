<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLogs extends Model
{
    use HasFactory;

    protected $table = 'daily_logs';
    
    public $timestamps = true;
}
