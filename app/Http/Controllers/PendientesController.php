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
    $query = Factura::where('status', 'Pendiente')->where('type', '!=', 'Reembolso');

    $area = $request->input('area');

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
        'area' => 'required', // El campo de área es requerido
        'anexos.*' => 'required|mimes:pdf,doc,docx|max:51200', // Ajusta los tipos de archivo según tus necesidades
    ], [
        'area.required' => 'Debes seleccionar un área antes de cargar el archivo.', // Mensaje de error personalizado para el campo de área
    ]);

    // Encuentra la factura por ID
    $factura = Factura::findOrFail($id);
    
    // Obtén el usuario actual autenticado
$user = Auth::user();

// Obtiene el nombre del área del usuario
$userArea = $user->area;

// Obtén las tres primeras letras del nombre del área
$areaInitials = substr($userArea, 0, 3);

// Combina el nombre del usuario con las tres primeras letras del área
$deliveredBy = $user->name . ' - ' . $areaInitials;
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
        'area_costo' => $request->input('area_costo'),
        'centro_costo' => $request->input('centro_costo'),
        'delivery_date' => now(),
        'delivered_by' => $deliveredBy,
        'subtype' => 'Adjuntada', 
    ]);
    $factura->subtype = 'Adjuntada';

   // Procesa los archivos anexos
   if ($request->hasFile('anexos')) {
    foreach ($request->file('anexos') as $index => $anexo) {
        $nombreCampo = 'anexo' . ($index + 1); // Calcula el nombre del campo de anexo
        $nombreArchivo = time() . '_' . ($index + 1) . '_' . $anexo->getClientOriginalName();
        $anexo->move(public_path('anexos'), $nombreArchivo);
        // Guarda el nombre del archivo en el campo correspondiente (anexo1, anexo2, etc.)
        $factura->$nombreCampo = $nombreArchivo;
    }
    // Guarda los cambios en la base de datos
    $factura->save();
}

    return redirect()->back()->with('success', 'Factura cargada con éxito.');
}

public function aprobar(Request $request, $id)
{
   
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
        'area_costo' => $request->input('area_costo'),
        'centro_costo' => $request->input('centro_costo'),
        'delivery_date' => now(),
        'delivered_by' => Auth::user()->name,
        'status' => 'Cargada', // Cambia el estado de la factura a 'Cargada'
    ]);
      $factura->subtype = 'Adjuntada';
 
        // Guarda los cambios en la base de datos
        $factura->save();


    // Redirecciona de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'Factura cargada con éxito.');
}


public function rechazar(Request $request, $id)
{
    
    // Encuentra la factura por ID
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
    $factura->area_costo = $request->input('area_costo');
    $factura->centro_costo = $request->input('centro_costo');
    
    $factura->note = $request->input('note');
    


   /* // Elimina los anexos de la carpeta de anexos
    for ($i = 1; $i <= 6; $i++) { // Ahora consideramos hasta 6 anexos
        $nombreArchivo = $factura->{'anexo' . $i};
        if ($nombreArchivo) {
            $rutaArchivo = public_path('anexos/' . $nombreArchivo);
            if (file_exists($rutaArchivo)) {
                unlink($rutaArchivo); // Elimina el archivo
            }
            // Elimina el nombre del anexo de la base de datos
            $factura->{'anexo' . $i} = null;
        }
    }*/

    // Guarda los cambios en la base de datos
    $factura->save();

    // Actualiza el subtype a "Rechazada"
    $factura->subtype = 'Rechazada';
    $factura->save();

    // Redirecciona de vuelta con un mensaje de éxito
    return redirect()->back()->with('success', 'Factura rechazada');
}


public function crearReembolso(Request $request)
{
    // Obtén los IDs de las facturas seleccionadas
    $ids = $request->input('ids');
    
    // Verifica si alguna factura no tiene el subtype "Adjuntada"
    $facturasNoAdjuntadas = Factura::whereIn('id', $ids)->where('subtype', '!=', 'Adjuntada')->exists();

    if ($facturasNoAdjuntadas) {
        return response()->json(['error' => 'Solo se pueden crear reembolsos cuando todas las facturas seleccionadas tienen el subtype "Adjuntada".'], 400);
    }

    if (empty($ids)) {
        return response()->json(['error' => 'No se seleccionaron facturas para crear el reembolso.'], 400);
    }

    // Crear un nuevo grupo de reembolso
    $reembolso = Reembolso::create([
        'consecutivo' => $this->generarConsecutivo(),
    ]);

    // Actualizar todas las facturas seleccionadas con el reembolso y el subtype
    Factura::whereIn('id', $ids)->update(['reembolso_id' => $reembolso->id, 'type' => 'Reembolso']);

    return response()->json(['success' => 'Se ha creado el reembolso correctamente.'], 200);
}

private function generarConsecutivo()
{
    // Obtener el último consecutivo registrado
    $ultimoReembolso = Reembolso::latest()->first();

    // Si no hay ningún reembolso registrado todavía, comenzar desde 1
    if (!$ultimoReembolso) {
        return 'R000001'; // O el formato que desees para tus consecutivos
    }

    // Extraer el número del último consecutivo y agregarle 1
    $ultimoNumero = intval(substr($ultimoReembolso->consecutivo, 1));
    $nuevoNumero = $ultimoNumero + 1;

    // Formatear el nuevo consecutivo con ceros a la izquierda si es necesario
    $nuevoConsecutivo = str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);

    return $nuevoConsecutivo;
}




public function editarAnexo(Request $request, $id, $numeroAnexo)
{
    // Encuentra la factura por ID
    $factura = Factura::findOrFail($id);

    // Validar y guardar el nuevo archivo adjunto
    if ($request->hasFile('nuevo_anexo')) {
        // Eliminar el anexo anterior si existe
        $nombreAnexoAnterior = $factura->{'anexo' . $numeroAnexo};
        if ($nombreAnexoAnterior) {
            Storage::delete('anexos/' . $nombreAnexoAnterior);
        }

        // Guardar el nuevo anexo
        $nombreNuevoAnexo = time() . '_' . $numeroAnexo . '_' . $request->file('nuevo_anexo')->getClientOriginalName();
        $request->file('nuevo_anexo')->storeAs('anexos', $nombreNuevoAnexo);
        $factura->{'anexo' . $numeroAnexo} = $nombreNuevoAnexo;
        // Guardar los cambios en la base de datos
        $factura->save();

        return redirect()->back()->with('success', 'Anexo ' . $numeroAnexo . ' actualizado con éxito.');
    }

    return redirect()->back()->with('error', 'No se seleccionó ningún archivo para actualizar.');
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
