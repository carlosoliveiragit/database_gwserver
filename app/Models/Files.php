<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Files extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Método para gerar um xid único antes de criar um novo arquivo
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            do {
                // Gera algo como: FILE_ABC123
                $xid = 'FL_' . Str::upper(Str::random(6));
            } while (self::where('xid', $xid)->exists());  // Verifica se o xid já existe

            // Atribui o xid gerado ao modelo
            $file->xid = $xid;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_xid','xid');
    }

    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_xid','xid');
    }

    public function system()
    {
        return $this->belongsTo(Systems::class, 'system_xid', 'xid');
    }

    public function type()
    {
        return $this->belongsTo(Types::class, 'type_xid','xid');
    }

    public function sector()
    {
        return $this->belongsTo(Sectors::class, 'sector_xid','xid');
    }
}
