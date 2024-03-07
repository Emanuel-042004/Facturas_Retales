<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    
    protected $fillable = [
      'name',
      'type',
      'folio',
      'issuer_nit',
      'issuer_name',
      'issue_date',
      'cude',
      'prefix',
      'subtype',
      'arrival_date',
      'location',
      'area',
      'note',
      'status',
      'delivery_date',
      'received_date',
      'delivered_by',
      'received_by',
      'anexo1',
      'anexo2',
      'anexo3',
      'anexo4',
      'anexo5',
      'anexo6',
      'reembolso_id',
    ];

    public function reembolso()
{
    return $this->belongsTo(Reembolso::class);
}
}
