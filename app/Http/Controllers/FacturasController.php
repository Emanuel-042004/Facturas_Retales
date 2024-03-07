<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;
use App\Mail\InvoiceCreated;
use Illuminate\Support\Facades\Mail;
use App\Models\User; 
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FacturasImport;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class FacturasController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function __construct()
    {
        $this->middleware('auth');
        
    }
    public function index()
    {
        return view('index');//
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      // Crear una nueva instancia de Factura con los datos del formulario
        $factura = new Factura([
            'name' => $request->input('name'),
            'type' => $request->input('type'),
            'folio' => $request->input('folio'),
            'issuer_nit' => $request->input('issuer_nit'),
            'issuer_name' => $request->input('issuer_name'),
            'cude' => $request->input('cude'),
            'arrival_date' => $request->input('arrival_date'),
            'issue_date' => $request->input('issue_date'),
            'prefix' => $request->input('prefix'),
            'area' => $request->input('area'),
            'note' => $request->input('note'),
            
            'status' => 'Pendiente',

        ]);

        // Guardar la factura en la base de datos
        $factura->save();
 // Enviar el correo electrónico a los usuarios con la misma área
        $usuariosConMismaArea = User::where('area', $factura->area)->get();
        foreach ($usuariosConMismaArea as $usuario) {
            Mail::to($usuario->email)->send(new InvoiceCreated($factura));
        }

 // Redireccionar a la vista principal después de crear
        return redirect()->route('facturas.index')->with('success', 'Factura creada con éxito.');
    }

    /*public function importExcel(Request $request){

        $file = $request->file('file');
        Excel::import(new FacturasImport, $file);
        return back()->with('message', 'Importacion completada');
    }*/


// ...

public function importExcel(Request $request)
{
    $file = $request->file('file');

    // Cargar el archivo con PhpSpreadsheet
    $spreadsheet = IOFactory::load($file);

    // Obtener la primera hoja del libro de trabajo
    $sheet = $spreadsheet->getActiveSheet();

    // Eliminar las filas vacías
    foreach ($sheet->getRowIterator() as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $nonEmptyCells = 0;
        foreach ($cellIterator as $cell) {
            if (!is_null($cell->getValue())) {
                $nonEmptyCells++;
            }
        }

        // Si la fila no tiene celdas no vacías, eliminarla
        if ($nonEmptyCells === 0) {
            $sheet->removeRow($row->getRowIndex(), 1);
        }
    }

    // Exportar el archivo modificado a una ubicación temporal
    $tempPath = storage_path('app/temp_import_file.xlsx');
    $writer = new Xlsx($spreadsheet);
    $writer->save($tempPath);

    // Importar el archivo modificado
    Excel::import(new FacturasImport, $tempPath);

    // Eliminar el archivo temporal después de la importación
    unlink($tempPath);

    return back()->with('message', 'Importación completada');
}


    /**
     * Display the specified resource.
     */
    public function show(Factura $factura)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Factura $factura)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Factura $factura)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factura $factura)
    {
        //
    }
}
