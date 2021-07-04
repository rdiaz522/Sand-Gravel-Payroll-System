<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $table = 'locations';

    protected $fillable = [
        'name',
    ];

    public $timestamps = true;

    public function timeLogs()
    {
        return $this->belongsTo(Timelogs::class,'id','department_id');
    }
}
