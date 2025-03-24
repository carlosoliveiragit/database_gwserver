<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Profiles extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // Gerar um xid único antes de criar um novo perfil
        static::creating(function ($profile) {
            do {
                // Gera algo como: PROFILE_ABC123
                $xid = 'PF_' . Str::upper(Str::random(6));
            } while (self::where('xid', $xid)->exists());  // Verifica se o xid já existe

            // Atribui o xid gerado ao modelo
            $profile->xid = $xid;
        });
    }
}
