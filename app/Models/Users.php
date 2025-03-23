<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sector()
    {
        return $this->belongsTo(Sectors::class);
    }

    public function profile()
    {
        return $this->belongsTo(Profiles::class);
    }
}
