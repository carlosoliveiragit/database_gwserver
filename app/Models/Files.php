<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }

    public function system()
    {
        return $this->belongsTo(Systems::class);
    }

    public function type()
    {
        return $this->belongsTo(Types::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sectors::class);
    }
}