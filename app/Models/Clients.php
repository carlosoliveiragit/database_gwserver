<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Clients extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // Gerar um xid único antes de criar um novo perfil
        static::creating(function ($client) {
            do {
                // Gera algo como: PROFILE_ABC123
                $xid = 'CL_' . Str::upper(Str::random(6));
            } while (self::where('xid', $xid)->exists());  // Verifica se o xid já existe

            // Atribui o xid gerado ao modelo
            $client->xid = $xid;
        });
    }
}
