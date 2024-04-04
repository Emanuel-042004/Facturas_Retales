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
use Illuminate\Support\Facades\Storage;

class CargadosController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las facturas con estado 'pending'
        $query = Factura::where('status', 'Cargada')->where('type', '!=', 'Reembolso');
    
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
    
        $cargados = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    
        
        
    
        // Retornar la vista 'entregados' con las facturas entregados y el área seleccionada
        return view('cargados.cargados', compact('cargados', 'area'));
    } //


    public function rechazar(Request $request, $id){
     
        $factura = Factura::findOrFail($id);
        $factura->type = $request->input('type');
        $factura->folio = $request->input('folio');
        $factura->issuer_name = $request->input('issuer_name');
        $factura->issuer_nit = $request->input('issuer_nit');
        $factura->prefix = $request->input('prefix');
        $factura->area = $request->input('area');
        $factura->costo1 = $request->input('costo1');
        $factura->costo2 = $request->input('costo2');
        $factura->costo3 = $request->input('costo3');
        $factura->costo4 = $request->input('costo4');
        $factura->note = $request->input('note');

        $factura->status = 'Pendiente';
        $factura->subtype = 'FIN/Rechazada';
        $factura->save();

        return redirect()->back()->with('success', 'Factura rechazada');

    }
    public function causarFactura(Request $request, $id)
{
    if (!$request->hasFile('causaciones')) {
        return redirect()->back()->with('error', 'Debes adjuntar al menos una causación.');
    }
    // Valida y guarda los archivos PDF
    $request->validate([
        'causaciones.*' => 'required|mimes:pdf,doc,docx|max:2048', // Ajusta los tipos de archivo según tus necesidades
    ]);
    // Encuentra la factura por ID
    $factura = Factura::findOrFail($id);
    // Actualiza los datos de la factura con los valores del formulario
    $factura->update([
        'type' => $request->input('type'),
        'folio' => $request->input('folio'),
        'issuer_name' => $request->input('issuer_name'),
        'issuer_nit' => $request->input('issuer_nit'),
        'area' => $request->input('area'),
        'note' => $request->input('note'),
        'costo1' => $request->input('costo1'),
        'costo2' => $request->input('costo2'),
        'costo3' => $request->input('costo3'),
        'costo4' => $request->input('costo4'),
        'status' => 'Causada',
        'subtype' => 'Adjuntada', 
    ]);
    $factura->subtype = 'Adjuntada';
    // Procesa los archivos de causación
    if ($request->hasFile('causaciones')) {
        $contadorCausaciones = 1; // Inicializa el contador de causaciones
        foreach ($request->file('causaciones') as $causacion) {
            $nombreArchivo = time() . '_' . $contadorCausaciones . '_' . $causacion->getClientOriginalName();
            $causacion->move(public_path('causaciones'), $nombreArchivo);
            // Guarda el nombre del archivo en el campo correspondiente (causacion1, causacion2, etc.)
            $factura->{'causacion' . $contadorCausaciones} = $nombreArchivo;
            $contadorCausaciones++;
        }
        // Guarda los cambios en la base de datos
        $factura->save();
    }
    // Redirecciona de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'Factura cargada con éxito.');
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
    

    /*// Obtén el nombre del usuario financiero
    $usuarioFinanciero = User::where('area', 'Financiera')->first();

    // Saludos para el correo electrónico
    $userSalutation = "Hola " . Auth::user()->name;
    $financieroSalutation = "Hola " . $usuarioFinanciero->name;

    // Envía el correo al usuario que entregó la factura
    Mail::to(Auth::user()->email)->send(new InvoiceDelivered($factura, Auth::user(), $userSalutation));

    // Envía el correo al usuario financiero
    Mail::to($usuarioFinanciero->email)->send(new InvoiceDelivered($factura, $usuarioFinanciero, $financieroSalutation));*/

    // Redirecciona de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'Factura cargada con éxito.');
}

public function pagosindex(Request $request)
{
    // Obtener todas las facturas con estado 'pending'
    $query = Factura::where('status', 'Pagada')->where('type', '!=', 'Reembolso');

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
    $page = $request->input('page', 1); // Página actual, por v defecto es 1

    $total = $query->count();
    $results = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

    $pagados = new LengthAwarePaginator($results, $total, $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);

    
    

    // Retornar la vista 'entregados' con las facturas entregados y el área seleccionada
    return view('pagados.pagados', compact('pagados', 'area'));
} //


}
