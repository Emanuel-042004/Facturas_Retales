<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reembolso;
use App\Models\Factura;

class ReembolsosController extends Controller
{
    //

    public function index(){

        $reembolsos = Reembolso::paginate(5);

        return view('reembolsos.reembolsos', compact('reembolsos'));
    }
    
    public function verFacturas($reembolsoId)
    {
        // Busca el reembolso por ID
        $reembolso = Reembolso::findOrFail($reembolsoId);
        
        // Obtén las facturas asociadas al reembolso
        $facturas = Factura::where('reembolso_id', $reembolsoId)->get();
        
        // Retorna la vista con los datos del reembolso y las facturas
        return view('pendientes.pendientes', compact('reembolso', 'facturas'));
    }
}
