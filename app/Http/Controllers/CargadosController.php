<?php

namespace App\Http\Controllers;
use App\Models\Factura;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Mail\InvoiceReceived;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
 // Asegúrate de tener la clase Carbon importada si aún no lo has hecho


class CargadosController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las facturas con estado 'pending'
        $query = Factura::where('status', 'Cargada');
    
        // Obtener el área de la solicitud, si está presente
        $area = $request->input('area');
    
        // Filtrar por área si se proporciona
        if ($area) {
            $query->where('area', $area);
        }
        $perPage = 10; // Número de elementos por página
        $page = $request->input('page', 1); // Página actual, por defecto es 1
    
        $total = $query->count();
        $results = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
    
        $cargados = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    
        
        
    
        // Retornar la vista 'entregados' con las facturas entregados y el área seleccionada
        return view('cargados.cargados', compact('cargados', 'area'));
    } //

}
