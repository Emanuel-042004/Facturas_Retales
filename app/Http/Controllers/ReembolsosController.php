<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reembolso;
use App\Models\Factura;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReembolsosController extends Controller
{
    //

    public function index(){

        $reembolsos = Reembolso::orderBy('id', 'desc')->paginate(7);

        return view('reembolsos.reembolsos', compact('reembolsos'));
    }
    
  

public function reembolsoscausados(Request $request)
{
    // Obtener todas las facturas con estado 'Causada' y tipo 'Reembolso'
    $query = Factura::where('status', 'Causada')->where('type', 'Reembolso');
    
    // Obtener el área de la solicitud, si está presente
    $area = $request->input('area');
    
    
    $search = $request->input('q');

    if ($search) {
        $query->where(function ($subquery) use ($search) {
            $factura = new Factura();
            $fillableFields = $factura->getFillable();
            foreach ($fillableFields as $field) {
                $subquery->orWhere($field, 'like', "%{$search}%");
            }
        });
    }

    // Filtrar por área si se proporciona
    if ($area) {
        $query->where('area', $area);
    }
    
    $perPage = 10; // Número de elementos por página
    $page = $request->input('page', 1); // Página actual, por defecto es 1
    
    $total = $query->count();
    $results = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
    
    // Acceder al consecutivo del reembolso para cada factura
    foreach ($results as $factura) {
        $consecutivo = $factura->reembolso->consecutivo; // Asumiendo que tienes una relación en tu modelo Factura llamada 'reembolso'
        $factura->consecutivo_reembolso = $consecutivo; // Agregar el consecutivo al objeto factura para pasar a la vista
    }
    
    $facturas = new LengthAwarePaginator($results, $total, $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);
    
    // Retorna la vista con los datos de las facturas y el área
    return view('causados.reembolsos', compact('facturas', 'area'));
}
/*public function consecutivoComprobantes(Request $request, $reembolsoId)
{
    // Obtener todas las facturas asociadas al reembolso específico
    $facturas = Factura::where('reembolso_id', $reembolsoId)->get();

    // Actualizar las facturas con el consecutivo de comprobantes y otros campos
    foreach ($facturas as $factura) {
        $factura->update([
            'con_comprobante' => $request->input('con_comprobante'),
            'status' => 'Finalizada',
            'subtype' => 'Adjuntada', 
        ]);
    }

    // Redireccionar con un mensaje de éxito
    return view('reembolsos.reembolsos')->with('reembolsoId', $reembolsoId);
}*/




}
