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
use App\Models\GrupoReembolso;
use App\Models\Reembolso;


class PendientesController extends Controller
{

public function index(Request $request)
{
    $query = Factura::where('status', 'Pendiente');

    $area = $request->input('area');

    if ($area) {
        $query->where('area', $area);
    }

    $perPage = 15; // Número de elementos por página
    $page = $request->input('page', 1); // Página actual, por defecto es 1
    $query->orderBy('id', 'desc');

    $total = $query->count();
    $results = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

    $pendientes = new LengthAwarePaginator($results, $total, $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);

    return view('pendientes.pendientes', compact('pendientes', 'area'));
}


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

public function aprobar(Request $request, $id)
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
        'status' => 'Cargada', // Cambia el estado de la factura a 'Cargada'
    ]);
      $factura->subtype = 'Aprobada';
 
        // Guarda los cambios en la base de datos
        $factura->save();
    

    // Obtén el nombre del usuario financiero
   /* $usuarioFinanciero = User::where('area', 'Financiera')->first();

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


public function crearReembolso(Request $request)
{
    // Obtén los IDs de las facturas seleccionadas
    $ids = $request->input('ids');
    

    if (empty($ids)) {
        return redirect()->back()->with('error', 'No se seleccionaron facturas para crear el reembolso.');
    }

    // Crear un nuevo grupo de reembolso
    $reembolso = Reembolso::create([
        'consecutivo' => $this->generarConsecutivo(),
    ]);

    // Actualizar todas las facturas seleccionadas con el reembolso y el subtype
    Factura::whereIn('id', $ids)->update(['reembolso_id' => $reembolso->id, 'type' => 'Reembolso']);

    return redirect()->back()->with('success', 'Se ha creado el reembolso correctamente.');
}
private function generarConsecutivo()
{
    // Obtener el último consecutivo registrado
    $ultimoReembolso = Reembolso::latest()->first();

    // Si no hay ningún reembolso registrado todavía, comenzar desde 1
    if (!$ultimoReembolso) {
        return 'R001'; // O el formato que desees para tus consecutivos
    }

    // Extraer el número del último consecutivo y agregarle 1
    $ultimoNumero = intval(substr($ultimoReembolso->consecutivo, 1));
    $nuevoNumero = $ultimoNumero + 1;

    // Formatear el nuevo consecutivo con ceros a la izquierda si es necesario
    $nuevoConsecutivo = 'R' . str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);

    return $nuevoConsecutivo;
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
/*public function cambiarTipoFacturas(Request $request)
{
    $tipo = $request->input('tipo');
    $ids = $request->input('ids');

    // Crear un nuevo grupo de reembolso
    $reembolso = Reembolso::create([
        'consecutivo' => $this->generarConsecutivo(), // Implementa la lógica para generar el consecutivo
    ]);

    // Asignar las facturas seleccionadas al grupo de reembolso creado
    Factura::whereIn('id', $ids)->update(['type' => $tipo, 'reembolso_id' => $reembolso->id]);

    return response()->json(['success' => true]);
}*/



/*public function cambiarTipoFacturas(Request $request)
{
    $tipo = $request->input('tipo');
    $ids = $request->input('ids');

    
    Factura::whereIn('id', $ids)->update(['type' => $tipo]);

    return response()->json(['success' => true]);
}*/


/*public function cambiarTipoFacturas(Request $request)
{
    $tipo = $request->input('tipo');
    $ids = $request->input('ids');

    // Crear un nuevo grupo de reembolso
    $consecutivo = date('YmdHis');
$grupoReembolso = GrupoReembolso::create(['consecutivo' => $consecutivo]);

    // Asociar las facturas seleccionadas al nuevo grupo de reembolso
    foreach ($ids as $id) {
        Reembolso::create([
            'factura_id' => $id,
            'grupo_reembolso_id' => $grupoReembolso->id,
        ]);
    }

    // Actualizar el tipo de las facturas seleccionadas a "Reembolso"
    Factura::whereIn('id', $ids)->update(['type' => $tipo]);

    return response()->json(['success' => true]);
}*/


public function rechazar(Request $request, $id)
{
    // Encuentra la factura por ID
    $factura = Factura::findOrFail($id);

    // Elimina los anexos de la carpeta de anexos
    for ($i = 1; $i <= 6; $i++) { // Ahora consideramos hasta 6 anexos
        $nombreArchivo = $factura->{'anexo' . $i};
        if ($nombreArchivo) {
            $rutaArchivo = public_path('anexos/' . $nombreArchivo);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo); // Elimina el archivo
            }
        }
    }

    // Elimina los nombres de los anexos de la base de datos
    $factura->anexo1 = null;
    $factura->anexo2 = null;
    $factura->anexo3 = null;
    $factura->anexo4 = null;
    $factura->anexo5 = null;
    $factura->anexo6 = null;

    // Guarda los cambios en la base de datos
    $factura->save();

    // Actualiza el subtype a "Rechazada"
    $factura->subtype = 'Rechazada';
    $factura->save();

    return redirect()->back()->with('success', 'Factura rechazada');
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
