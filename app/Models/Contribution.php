<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'session',
        'contribution_type',
        'payment_status',
        'poster_file',
        'payment_receipt',
        'downloads',
    ];
}
