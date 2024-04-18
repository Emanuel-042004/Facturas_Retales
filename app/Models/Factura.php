<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    
    protected $fillable = [

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
      'costo1',
      'costo2',
      'costo3',
      'costo4',
      'causacion1',
      'causacion2',
      'causacion3',
      'causacion4',
      'causacion5',
      'causacion6',
      'comprobante1',
      'comprobante2',
      'comprobante3',
      'con_comprobante',
      'reembolso_id',
      'egreso',
      'centro_costo',
      'area_costo',

    ];

    public function reembolso()
{
    return $this->belongsTo(Reembolso::class);
}
public function scopeFilter($query, $search)
{
    if ($search){
        $query->where(function ($query) use ($search) {
            foreach ($this->fillable as $field) {
                $query->orWhere($field, 'like', '%' . $search . '%');
            }
        });
    }
    return $query;
}
}
