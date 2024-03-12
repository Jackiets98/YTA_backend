<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    use HasFactory;

    protected $table = 'admin_notifications'; // Specify the table name

    protected $fillable = [
        'message',
    ];
}
