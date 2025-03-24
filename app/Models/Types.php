<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Types extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Método para gerar um xid único antes de criar um novo tipo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($type) {
            do {
                // Gera algo como: TYPE_ABC123
                $xid = 'TP_' . Str::upper(Str::random(6));
            } while (self::where('xid', $xid)->exists());  // Verifica se o xid já existe

            // Atribui o xid gerado ao modelo
            $type->xid = $xid;
        });
    }
}
