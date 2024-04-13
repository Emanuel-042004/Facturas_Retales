<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;
use Illuminate\Pagination\LengthAwarePaginator;

class FinalizadasController extends Controller
{
   
    public function index(Request $request)
    {
        // Obtener todas las facturas con estado 'pending'
        $query = Factura::where('status', 'Finalizada')->where('type', '!=', 'Reembolso');
    
        // Obtener el área de la solicitud, si está presente
        $area = $request->input('area');
    
        // Filtrar por área si se proporciona
        if ($area) {
            $query->where('area', $area);
        }
    
    
    
    
    
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
    
        $perPage = 10; // Número de elementos por página
        $page = $request->input('page', 1); // Página actual, por defecto es 1
    
        $total = $query->count();
        $results = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
    
        $finalizadas = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    
        
        
    
        // Retornar la vista 'entregados' con las facturas entregados y el área seleccionada
        return view('finalizadas.finalizadas', compact('finalizadas', 'area'));
    } //
     //
}
