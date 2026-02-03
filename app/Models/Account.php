<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['username', 'password', 'email', 'phone', 'role'];
    protected $hidden = ['password']; // ซ่อนรหัสผ่านเวลาส่งออก API
}
