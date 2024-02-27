<?php

namespace App\Http\Controllers;
use App\Models\Factura;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Mail\InvoiceDelivered;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

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


    public function rechazar(Request $request, $id){
     
        $factura = Factura::find($id);
        $factura->status = 'Rechazada';
        $factura->save();

        return redirect()->back()->with('success', 'Factura rechazada');

    }


    public function entregar(Request $request, $id)
{
   
    // Encuentra la factura por ID
    $factura = Factura::findOrFail($id);

    // Actualiza los datos de la factura con los valores del formulario
    $factura->update([
        'type' => $request->input('type'),
        'name' => $request->input('name'),
        'folio' => $request->input('folio'),
        'issuer_name' => $request->input('issuer_name'),
        'issuer_nit' => $request->input('issuer_nit'),
        'area' => $request->input('area'),
        'delivery_date' => now(),
        'delivered_by' => Auth::user()->name,
        'status' => 'Entregada', // Cambia el estado de la factura a 'Entregada'
    ]);

 
        // Guarda los cambios en la base de datos
        $factura->save();
    

    // Obtén el nombre del usuario financiero
    $usuarioFinanciero = User::where('area', 'Financiera')->first();

    // Saludos para el correo electrónico
    $userSalutation = "Hola " . Auth::user()->name;
    $financieroSalutation = "Hola " . $usuarioFinanciero->name;

    // Envía el correo al usuario que entregó la factura
    Mail::to(Auth::user()->email)->send(new InvoiceDelivered($factura, Auth::user(), $userSalutation));

    // Envía el correo al usuario financiero
    Mail::to($usuarioFinanciero->email)->send(new InvoiceDelivered($factura, $usuarioFinanciero, $financieroSalutation));

    // Redirecciona de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'Factura cargada con éxito.');
}


}
