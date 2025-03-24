<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Users extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // Gerar um xid único antes de criar um novo usuário
        static::creating(function ($user) {
            do {
                // Gera algo como: USER_ABC123
                $xid = 'US_' . Str::upper(Str::random(6));
            } while (self::where('xid', $xid)->exists());  // Verifica se o xid já existe

            // Atribui o xid gerado ao modelo
            $user->xid = $xid;
        });
    }

    // Relacionamento com o setor
    public function sector()
    {
        return $this->belongsTo(Sectors::class, 'sector_xid', 'xid');
    }

    // Relacionamento com o perfil
    public function profile()
    {
        return $this->belongsTo(Profiles::class, 'profile_xid', 'xid');
    }
}
