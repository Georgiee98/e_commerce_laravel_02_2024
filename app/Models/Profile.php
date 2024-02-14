<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'adress_number',
        'city',
        'state',
        'zip_code',
        'phone',
        'surname',
        'username',
        'bday',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}