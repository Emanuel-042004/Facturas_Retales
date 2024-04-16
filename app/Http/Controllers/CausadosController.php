<?php

namespace App\Http\Controllers;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;


class CausadosController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todas las facturas con estado 'pending'
        $query = Factura::where('status', 'Causada')->where('type', '!=', 'Reembolso');
    
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
    
        $causados = new LengthAwarePaginator($results, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
    
       
        
    
        // Retornar la vista 'recibidos' con las facturas recibidos y el área seleccionada
        return view('causados.causados', compact('causados', 'area'));
    } //

    public function finalizar(Request $request, $id)
    {
        if (!$request->hasFile('comprobantes')) {
            return redirect()->back()->with('error', 'Debes adjuntar al menos una causación.');
        }
        // Valida y guarda los archivos PDF
        $request->validate([
            'comprobantes.*' => 'required|mimes:pdf,doc,docx|max:51200', // Ajusta los tipos de archivo según tus necesidades
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
            'area_costo' => $request->input('area_costo'),
            'centro_costo' => $request->input('centro_costo'),
            'status' => 'Finalizada',
            'subtype' => 'Adjuntada', 
             
        ]);
        
        // Procesa los archivos de causación
        if ($request->hasFile('comprobantes')) {
            $contadorComprobantes = 1; // Inicializa el contador de comprobantes
            foreach ($request->file('comprobantes') as $comprobante) {
                $nombreArchivo = time() . '_' . $contadorComprobantes . '_' . $comprobante->getClientOriginalName();
                $comprobante->move(public_path('comprobantes'), $nombreArchivo);
                // Guarda el nombre del archivo en el campo correspondiente (causacion1, causacion2, etc.)
                $factura->{'comprobante' . $contadorComprobantes} = $nombreArchivo;
                $contadorComprobantes++;
            }
            // Guarda los cambios en la base de datos
            $factura->save();
        }
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
    $page = $request->input('page', 1); // Página actual, por defecto es 1

    $total = $query->count();
    $results = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

    $pagados = new LengthAwarePaginator($results, $total, $perPage, $page, [
        'path' => LengthAwarePaginator::resolveCurrentPath(),
    ]);

    
    

    // Retornar la vista 'entregados' con las facturas entregados y el área seleccionada
    return view('pagados.pagados', compact('pagados', 'area'));
} //


public function rechazar_pago(Request $request, $id){
     
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
    $factura->area_costo = $request->input('area_costo');
    $factura->centro_costo = $request->input('centro_costo');
    $factura->status = 'Causada';
    $factura->subtype = 'Pag/No Aprobado';
    $factura->save();

    return redirect()->back()->with('success', 'Factura rechazada');

}

public function comprobarFactura(Request $request, $id)
    {
        if (!$request->hasFile('egreso')) {
            return redirect()->back()->with('error', 'Debes adjuntar al menos una causación.');
        }
        // Valida y guarda los archivos PDF
        $request->validate([
            'egreso.*' => 'required|mimes:pdf,doc,docx|max:51200', // Ajusta los tipos de archivo según tus necesidades
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
            'area_costo' => $request->input('area_costo'),
            'centro_costo' => $request->input('centro_costo'),
            'status' => 'Pagada',
            'subtype' => 'Adjuntada',
            
        ]);
        
        
         // Procesa el archivo de egreso
    if ($request->hasFile('egreso')) {
        $egreso = $request->file('egreso');
        $nombreArchivo = time() . '_' . $egreso->getClientOriginalName();
        $egreso->move(public_path('egresos'), $nombreArchivo);

        $factura->egreso = $nombreArchivo;
    }
            // Guarda los cambios en la base de datos
            $factura->save();
        
        // Redirecciona de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Factura cargada con éxito.');
    }
    

 //
}
