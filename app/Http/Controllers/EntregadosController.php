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


class EntregadosController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las facturas con estado 'pending'
        $query = Factura::where('status', 'delivered');
    
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
    
        $entregados = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    
        
        
    
        // Retornar la vista 'entregados' con las facturas entregados y el área seleccionada
        return view('entregados.entregados', compact('entregados', 'area'));
    } //

    public function recibirFactura(Request $request, $id)
    {
        // Lógica para actualizar el estado de la factura a 'received' en la base de datos
        $factura = Factura::find($id);
        $factura->status = 'received';
        $factura->received_date = Carbon::now();
        $user = Auth::user();
        $factura->received_by = $user->name;
        $factura->save();

        $deliveredUser = User::where('name', $factura->delivered_by)->first();
        Mail::to($deliveredUser->email)->send(new InvoiceReceived($factura));
    

        // Puedes agregar más lógica según sea necesario

        return redirect()->back()->with('success', 'Factura recibida con éxito.');
    }

    public function eliminarFactura($id)
    {
        $factura = Factura::find($id);
    
        if ($factura) {
            $factura->delete();
    
            return redirect()->back()->with('success', 'Factura eliminada con éxito.');
        } else {
            return redirect()->back()->with('error', 'No se pudo encontrar la factura.');
        }
    }
}
