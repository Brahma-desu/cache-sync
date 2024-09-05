<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRecords extends Model
{
    use HasFactory;

    protected $table = 'user_records';
    protected $primaryKey = 'id'; 
}