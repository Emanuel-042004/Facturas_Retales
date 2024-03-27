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
    
    public function verFacturas($reembolsoId)
    {
        // Busca el reembolso por ID
        $reembolso = Reembolso::findOrFail($reembolsoId);
        
        // Obtén las facturas asociadas al reembolso
        $facturas = Factura::where('reembolso_id', $reembolsoId)->get();
        
        // Retorna la vista con los datos del reembolso y las facturas
        return view('pendientes.pendientes', compact('reembolso', 'facturas'));
    }

    public function cargarFactura(Request $request, $id)
{
    if (!$request->hasFile('anexos')) {
        return redirect()->back()->with('error', 'Debes adjuntar al menos un anexo.');
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
        'delivery_date' => now(),
        'delivered_by' => Auth::user()->name,
        'subtype' => 'Adjuntada', 
    ]);
    $factura->subtype = 'Adjuntada';

   // Procesa los archivos anexos
if ($request->hasFile('anexos')) {
    $contadorAnexos = 1; // Inicializa el contador de anexos
    foreach ($request->file('anexos') as $anexo) {
        $nombreArchivo = time() . '_' . $contadorAnexos . '_' . $anexo->getClientOriginalName();
        $anexo->move(public_path('anexos'), $nombreArchivo);
        // Guarda el nombre del archivo en el campo correspondiente (anexo1, anexo2, etc.)
        $factura->{'anexo' . $contadorAnexos} = $nombreArchivo;
        $contadorAnexos++;
    }
    // Guarda los cambios en la base de datos
    $factura->save();
}


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
}
