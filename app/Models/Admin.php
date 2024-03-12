<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins'; // Specify the table name for your admins
    protected $primaryKey = 'id'; // Specify the primary key for your admins table

    protected $fillable = [
        'email',
        'password',
    ];
}
