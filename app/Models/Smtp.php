<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    protected $table = 'smtp';

    protected $fillable = [
        'mailer',
        'host',
        'port',
        'user_name',
        'password',
        'mail_from_address',
        'mail_from_name'
    ];
}
