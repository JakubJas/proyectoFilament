<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cliente extends Model
{
    use HasFactory;

    protected $table = "clientes";

    // Asignar campos que serán rellenables desde una Request.
    protected $fillable = [
        'nombre',
        'tipo',
        'email',
        'direccion',
        'ciudad',
        'codigo_postal'
    ];

    // Un cliente puede tener muchas facturas.
    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }
}
