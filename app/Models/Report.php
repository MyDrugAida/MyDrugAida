<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    public function pharmacist()
    {
        return $this->belongsTo(User::class,'firebase_uid','user_uid');
    }
}
