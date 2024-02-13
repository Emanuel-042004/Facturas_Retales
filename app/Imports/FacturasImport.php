<?php

namespace App\Imports;

use App\Models\Factura;
use Maatwebsite\Excel\Concerns\ToModel;

class FacturasImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
   /* public function model(array $row)
    {
        return new Factura([
            'name' => $row[0],
            'type' => $row[1],	
            'folio'	=> $row[2],
            'prefix'=> $row[3],	
            'issuer_nit'=> $row[4],	
            'issuer_name'=> $row[5],	
            'cude'	=> $row[6],
        ]);
    }*/

    public function model(array $row)
{
    // Verificar si al menos una celda contiene datos
    if (array_filter($row)) {
        return new Factura([
            'name' => $row[0],
            'type' => $row[1],
            'folio' => $row[2],
            'prefix' => $row[3],
            'issuer_nit' => $row[4],
            'issuer_name' => $row[5],
            'cude' => $row[6],
        ]);
    }

    // Si todas las celdas están vacías, retornar null
    return null;
}

}
