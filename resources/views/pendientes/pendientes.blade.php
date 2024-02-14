@extends('layouts.header')

@section('content')

<div class="tablas">
  <div class="cardHeader">
    <h2>Facturas DIAN</h2>
    <a href="#" class="btn">View All</a>
  </div>
  <table>
    <thead>
      <td></td>
      <td>Estado</td>
      <td>Area</td>
      <td>Nombre</td>
      <td>Folio</td>
      <td>Nombre de Emisor</td>
      <td>NIT de Emisor</td>
      <td>Acciones</td>

      </tr>
    </thead>
    <tbody>
      @foreach ($pendientes as $factura)
      @if (!$area || $factura->area === $area)

      <td>
        <input type="checkbox" class="form-check-input" name="selectedFacturas[]" value="{{ $factura->id }}">
      </td>
      <td ><span class="status pending">{{ $factura->status}}</span></td>
      <td>{{ $factura->area }}</td>
      <td>{{ $factura->name }}</td>
      <td>{{ $factura->folio}}</td>
      <td>{{ $factura->issuer_name}}</td>
      <td>{{ $factura->issuer_nit }}</td>
      <td class="icon-cell">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill"
          viewBox="0 0 16 16" data-bs-toggle="modal" data-bs-target="#facturaModal{{$factura->id}}"
          style="margin-right: 10px;">
          <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
          <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
        </svg>
      </td>

      </tr>
      @endif
      @endforeach

    </tbody>
  </table>
  
  <!-- Estilos Bootstrap para la paginación -->
  <div>
    <ul class="pagination">
      <li class="{{ $pendientes->onFirstPage() ? 'disabled' : '' }}">
        <a href="{{ $pendientes->previousPageUrl() }}" aria-label="Anterior">
          <span aria-hidden="true">« Anterior</span>
        </a>
      </li>

      <li class="{{ $pendientes->hasMorePages() ? '' : 'disabled' }}">
        <a href="{{ $pendientes->nextPageUrl() }}" class="page-link" aria-label="Siguiente">
          <span aria-hidden="true">Siguiente »</span>
        </a>
      </li>
    </ul>
</div>


</div>



<!-- MODAL -->
@foreach ($pendientes as $factura)
<div class="modal fade" id="facturaModal{{$factura->id}}" tabindex="-1" role="dialog"
  aria-labelledby="facturaModalLabel{{$factura->id}}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-xl" role="document">
    <!-- Cambiado modal-xl para hacerlo amplio -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="facturaLabel">Datos de Factura</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="entregarFacturaForm{{$factura->id}}" action="{{ route('entregar_factura', ['id' => $factura->id]) }}"
          method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-lg-6">
              <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="nombre">Nombre</label>
              <input type="text" class="form-control mb-2" id="name" name="name" value="{{$factura->name}}"
                placeholder="Nombre">
            </div>
            <div class="col-lg-6">
              <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="folio">Folio</label>
              <input type="text" class="form-control mb-2" id="folio" name="folio" value="{{$factura->folio}}"
                placeholder="Contrato">
            </div>
          </div>
          <!-- Resto del formulario -->
          <div class="row">
            <div class="col-lg-6">
              <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="issuer_name">Nombre Emisor</label>
              <input type="text" class="form-control mb-2" id="issuer_name" name="issuer_name"
                value="{{$factura->issuer_name}}">
            </div>
            <div class="col-lg-6">
              <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="issuer_nit">Nit Emisor</label>
              <input type="text" class="form-control mb-2" id="issuer_nit" name="issuer_nit"
                value="{{$factura->issuer_nit}}">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="prefix">Prefijo</label>
              <input type="text" class="form-control mb-2" id="prefix" name="prefix" value="{{$factura->prefix}}">
            </div>
            <div class="col-lg-6">
              <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="sede">Area</label>
              <select class="form-control mb-2" id="area" name="area">
                <option value="">Selecciona</option>
                <option value="Compras" @selected( "Compras"==$factura -> area)>Compras</option>
                <option value="Financiera" @selected( "Financiera"==$factura -> area)>Financiera</option>
                <option value="Logistica" @selected( "Logistica"==$factura -> area)>Logistica</option>
                <option value="Mantenimiento" @selected( "Mantenimiento"==$factura -> area)>Mantenimiento</option>
                <option value="Tecnologia" @selected( "Tecnologia"==$factura -> area)>Tecnologia</option>
              </select>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-lg-6">
              <label for="pdf1">ANEXOS</label>
              <div class="file-drop-area">
                <input type="file" class="form-control-file" id="pdf1" name="pdf1">
                <span class="file-msg">Arrastra y suelta aquí o haz clic para seleccionar un archivo</span>
              </div>
            </div>
            <div class="col-lg-6">
              <label for="pdf2">NO SE QUE MAS VA</label>
              <div class="file-drop-area">
                <input type="file" class="form-control-file" id="pdf2" name="pdf2">
                <span class="file-msg">Arrastra y suelta aquí o haz clic para seleccionar un archivo</span>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-danger"
          onclick="asignarArea({{ $factura->id }}, '{{ $factura->area }}')">Asignar a mi área</button>
        <button type="submit" class="btn btn-primary">Entregar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('pdf1').addEventListener(, function (e) {
    var fileName = e.target.files[0].name;
    var fileMsg = this.nextElementSibling;
    fileMsg.textContent = fileName;
    fileMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" heighill="currentColor" class="bi bi-folder2-open" viewBox="0 0 16 16"><path d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v.64c.57.265.94.876.856 1.546l-.64 5.124A2.5 2.5 0 0 1 12.733 15H3.266a2.5 2.5 0 0 1-2.481-2.19l-.64-5.124A1.5 1.5 0 0 1 1 6.14zM2 6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174.  3.334 5.82 3 5.264 3H2.5a.5.5 0 0 0-.5.5zm-.367 1a.5.5 0 0 0-.496.5 62l.64    A1.5 1.5 0 0 0 3.266 14h9.468a1.5 1.5 0    .489-1.314l.64-5.124A.5.5 0 0 0 14.367     /svg> ' + fileName;
  });

  doc    getElementById('pdf2').addEventListener('change', function (e) {
    var fileName = e.target.files[0].name;
    var fileMsg = this.nextElementSibling;
    fileMsg.textContent = fileName;
    fileMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder2-open" viewBox="0 0 16 16"><path d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v.64c.57.265.94.876.856 1.546l-.64 5.124A2.5 2.5 0 0 1 12.733 15H3.266a2.5 2.5 0 0 1-2.481-2.19l-.64-5.124A1.5 1.5 0 0 1 1 6.14zM2 6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174-1.154C6.37  334 5  3 5.264 3H2.5a.5.5 0 0 0-.5.5zm-.367 1a.5.5 0 0 0-.496.562l.64 5.12 4A1.5     0 0 3.266 14h9.468a1.5 1.5 0 0 0 1.489-    .64-5.124A.5.5 0 0 0 14.367 7z"/></svg>    ileName;
  });
</script>
@endforeach


@endsection