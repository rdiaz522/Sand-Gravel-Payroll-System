<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAdvanceDescription extends Model
{
    use HasFactory;

    protected $table = 'cash_advance_descriptions';
    
    protected $fillable = [
        'name',
    ];
}
