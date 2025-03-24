<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Sectors extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // Gerar um xid único antes de criar um novo setor
        static::creating(function ($sector) {
            do {
                // Gera algo como: SECTOR_ABC123
                $xid = 'SC_' . Str::upper(Str::random(6));
            } while (self::where('xid', $xid)->exists());  // Verifica se o xid já existe

            // Atribui o xid gerado ao modelo
            $sector->xid = $xid;
        });
    }
}
