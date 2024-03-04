<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoReembolso extends Model
{
    use HasFactory;
    protected $table = 'grupos_reembolsos';

    protected $fillable = ['consecutivo'];

    // Relación con los reembolsos
    public function reembolsos()
    {
        return $this->hasMany(Reembolso::class, 'grupo_reembolso_id');
    }
}
