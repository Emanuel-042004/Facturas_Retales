@extends('layouts.header')

@section('content')
<!-- Modal -->
<div class="modal fade" id="facturaModal" tabindex="-1" role="dialog" aria-labelledby="facturaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="facturaLabel">Datos de Factura</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    
                </button>
            </div>
            <div class="modal-body">
                <form id="" action="" method="POST">
                    <div class="row">
                        <div class="col-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control mb-2" id="nombre" name="nombre" placeholder="Nombre">
                        </div>
                        <div class="col-6">
                            <label for="contrato">Folio</label>
                            <input type="text" class="form-control mb-2" id="contrato" name="contrato"
                                placeholder="Contrato">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <label for="fechaLlegada">Fecha de Llegada</label>
                            <input type="date" class="form-control mb-2" id="fechaLlegada" name="fechaLlegada">
                        </div>
                        <div class="col-6">
                            <label for="fechaEntrega">Fecha de Entrega</label>
                            <input type="date" class="form-control mb-2" id="fechaEntrega" name="fechaEntrega">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-6">
                            <label for="sede">Sede</label>
                            <select class="form-control mb-2" id="sede" name="sede">
                                <option value="">Selecciona una sede</option>
                                <option value="sede1">Sede 1</option>
                                <option value="sede2">Sede 2</option>
                            </select>
                        </div>
                        <div class="col-6 mt-5">
                            <label for="archivo">
                                <img id="imagen" width="48" height="48"
                                    src="https://img.icons8.com/color/48/add-file.png" alt="add-file" />
                                Cargar Factura
                                <input type="file" id="archivo" name="archivo" style="display: none;">
                            </label>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <label for="nota">Nota</label>
                            <textarea class="form-control mb-2" id="nota" name="nota" rows="3"></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Crear</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
