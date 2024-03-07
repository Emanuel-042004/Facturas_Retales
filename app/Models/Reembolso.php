<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reembolso extends Model
{
    use HasFactory;

    protected $table = 'reembolsos';
    
    protected $fillable = [
        'consecutivo',
    ];

    public function facturas()
{
    return $this->hasMany(Factura::class);
}
}
