<?php

namespace App\Http\Controllers;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\InvoiceDelivered;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;


class PendientesController extends Controller
{

public function index(Request $request)
{
    $query = Factura::where('status', 'Pendiente');

    $area = $request->input('area');

    if ($area) {
        $query->where('area', $area);
    }

    $perPage = 10; // Número de elementos por página
    $page = $request->input('page', 1); // Página actual, por defecto es 1
    $query->orderBy('id', 'desc');

    $total = $query->count();
    $results = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

    $pendientes = new LengthAwarePaginator($results, $total, $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);

    return view('pendientes.pendientes', compact('pendientes', 'area'));
}

   /*public function cargarFactura(Request $request, $id)
{
    // Valida y guarda el archivo PDF
    $request->validate([
        'anexo1' => 'required|mimes:pdf,doc,docx|max:2048', // Ajusta los tipos de archivo según tus necesidades
    ]);

  
    $factura = Factura::findOrFail($id);

    // Actualiza la base de datos con los datos de entrega
    
   
    $factura->type = $request->input('type');
    $factura->name = $request->input('name');
    $factura->folio = $request->input('folio');
    $factura->issuer_name = $request->input('issuer_name');
    $factura->issuer_nit = $request->input('issuer_nit');
    $factura->area = $request->input('area');

    $factura->delivery_date = now();
    $user = Auth::user();
    $factura->delivered_by = $user->name;
   

    // Cambia el estado de la factura a 'delivered'
    $factura->status ='Cargada';

    if ($request->hasFile('anexo1')) {
        $anexo1 = $request->file('anexo1');
        $anexo1Nombre = time() . '_' . $anexo1->getClientOriginalName();
        $anexo1->move(public_path('anexos'), $anexo1Nombre); // Mueve el archivo a la carpeta deseada
 
        // Asigna el nombre del archivo a la factura
        $factura->anexo1 = $anexo1Nombre;
    }

    $factura->save();

    // Obtén el nombre del usuario financiero
    $usuarioFinanciero = User::where('area', 'Financiera')->first();

    // Cambia el saludo del correo según el nombre del usuario que entregó y del usuario financiero
    $userSalutation = "Hola $user->name";
    $financieroSalutation = "Hola $usuarioFinanciero->name";

    // Envía el correo al usuario que entregó la factura
    Mail::to($user->email)->send(new InvoiceDelivered($factura, $user, $userSalutation));

    // Envía el correo al usuario financiero
    Mail::to($usuarioFinanciero->email)->send(new InvoiceDelivered($factura, $usuarioFinanciero, $financieroSalutation));


    return redirect()->back()->with('success', 'Factura entregada con éxito.');
}*/

public function cargarFactura(Request $request, $id)
{
    if (!$request->hasFile('anexos')) {
        return redirect()->back()->with('error', 'Debes adjuntar al menos un anexo.');
    }

    // Valida y guarda los archivos PDF
    $request->validate([
        'anexos.*' => 'required|mimes:pdf,doc,docx|max:2048', // Ajusta los tipos de archivo según tus necesidades
    ]);

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
        'note' => $request->input('note'),
        'delivery_date' => now(),
        'delivered_by' => Auth::user()->name,
        'status' => 'Cargada', // Cambia el estado de la factura a 'Cargada'
    ]);

    // Procesa los archivos anexos
    if ($request->hasFile('anexos')) {
        foreach ($request->file('anexos') as $key => $anexo) {
            $nombreArchivo = time() . '_' . $key . '_' . $anexo->getClientOriginalName();
            $anexo->move(public_path('anexos'), $nombreArchivo);
            // Guarda el nombre del archivo en el campo correspondiente (anexo1, anexo2, etc.)
            $factura->{'anexo' . ($key + 1)} = $nombreArchivo;
        }
        // Guarda los cambios en la base de datos
        $factura->save();
    }

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



public function cambiarTipoFacturas(Request $request)
{
    $tipo = $request->input('tipo');
    $ids = $request->input('ids');

    
    Factura::whereIn('id', $ids)->update(['type' => $tipo]);

    return response()->json(['success' => true]);
}


public function anexo1 (Request $request){

    $request->validate([
        'anexo1'=> 'required|mimes:csv,txt,xls,ppt,pdf|max:2048',
    ]);

    $uploadedFile = $request->anexo1('anexo1');
    $anexo1= time() . $uploadedFile->getClientOriginalname();
    Storage::putFileAs('file/' . $anexo1, $uploadedFile, $anexo1);

    $upload = new Factura();
    $upload->anexo1 =$anexo1;
    $upload->save();

    
}


    public function eliminarFactura($id)
{
    $factura = Factura::find($id);

    if ($factura) {
        $factura->delete();

        return redirect()->back()->with('destroy', 'Factura eliminada con éxito.');
    } else {
        return redirect()->back()->with('error', 'No se pudo encontrar la factura.');
    }
}

public function asignarArea($id)
{
    $factura = Factura::find($id);

    // Verifica que la factura exista
    if (!$factura) {
        return redirect()->back()->with('error', 'No se pudo encontrar la factura.');
    }

    // Asigna el área del usuario autenticado a la factura
    $user = Auth::user();
    $factura->area = $user->area;

    // Guarda la factura en la base de datos
    $factura->save();

    return redirect()->back()->with('asignar', 'Factura asignada a tu área con éxito.');
}




    public function eliminarSeleccion(Request $request)
{
    $selectedFacturas = $request->input('selectedFacturas', []);
    $action = $request->input('action');

    if ($action === 'delete') {
        // Lógica para eliminar las facturas seleccionadas
        Factura::whereIn('id', $selectedFacturas)->delete();

        return redirect()->route('pendientes.index')->with('destroy', 'Facturas eliminadas correctamente.');
    }

    // Manejo de otra acción o escenario
    return redirect()->route('pendientes.index')->with('error', 'Acción no válida.');
}


    public function entregar_seleccion (Request $request){
    $selectedFacturas = $request->input('selectedFacturas', []);
    $action = $request->input('action');

    if ($action === 'submit') {
        // Lógica para entregar las facturas seleccionadas
        Factura::whereIn('id', $selectedFacturas)->delete();

        return redirect()->route('pendientes.index')->with('destroy', 'Facturas eliminadas correctamente.');
    }
    }
    
   /*public function edit(Factura $factura)
    {
        //
    }*/
}
