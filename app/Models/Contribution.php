<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    use HasFactory;

    protected $table = 'contributions';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'employee_id',
       'sss',
       'pagibig',
       'philhealth',
       'contribution_date',
   ];

   public $timestamps = true;

   public function employees()
   {
       return $this->hasMany(Employees::class);
   }
}
