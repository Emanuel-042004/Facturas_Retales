<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reembolso extends Model
{
    use HasFactory;
    protected $table = 'reembolsos';

    protected $fillable = ['factura_id', 'grupo_reembolso_id'];

    // Relación con la tabla de facturas
    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }

    // Relación con la tabla de grupos de reembolsos
    public function grupoReembolso()
    {
        return $this->belongsTo(GrupoReembolso::class, 'grupo_reembolso_id');
    }
}
