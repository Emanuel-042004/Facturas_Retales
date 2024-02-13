@extends('layouts.header')

@section('content')
<div class="col-8 mt-4">
  <button type="button" class="btn btn-dark" onclick="window.location.href='{{ url('/index') }}'">Volver</button>
  <h1 class="mt-4">Recibidos <img width="48" height="48" src="https://img.icons8.com/color/48/reading-confirmation.png"
      alt="reading-confirmation" /></h1>
  @include('layouts.areas')
  <div class="row">
  <div class="row">
  <div class="col-md-6 mt-4">
    <input type="text" id="searchInputRecibidos" class="form-control" placeholder="Buscar por nombre, folio, etc." />
  </div>
</div>

    <table class="table table-responsive mt-4">
      <thead class="table-header-success  bg-body-secondary">
        <tr>
        <th>Area</th>
          <th>Nombre</th>
          <th>Folio</th>
          <th>Nombre Emisor</th>
          <th>NIT Emisor</th>
          <th>Entregado</th>
          <th>Entregado por</th>
          <th>Recibido</th>
          <th>Recibido por</th>
          
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($recibidos as $factura)
        @if (!$area || $factura->area === $area)
        <tr class="tr-success tr-entre">
        <td>{{ $factura->area }}</td>
        <td>{{ $factura->name }}</td>
          <td>{{ $factura->folio}}</td>
          <td>{{ $factura->issuer_name}}</td>
          <td>{{ $factura->issuer_nit }}</td>
          <td>{{ $factura->delivery_date}}</td>
          <td>{{ $factura->delivered_by }}</td>
          <td>{{ $factura->received_date }}</td>
          <td>{{ $factura->received_by }}</td>
         
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
      <script>
  document.addEventListener("DOMContentLoaded", function () {
    var searchInputRecibidos = document.getElementById('searchInputRecibidos');

    // Agrega un evento para manejar cambios en el campo de búsqueda para la tabla de recibidos
    searchInputRecibidos.addEventListener('input', function () {
      var searchTerm = searchInputRecibidos.value.toLowerCase();

      // Filtra las filas de la tabla de recibidos según el término de búsqueda
      var rows = document.querySelectorAll('.tr-entre');

      rows.forEach(function (row) {
        var area = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        var name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        var folio = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        var issuerName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        var issuerNit = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
        var deliveryDate = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
        var deliveredBy = row.querySelector('td:nth-child(7)').textContent.toLowerCase();
        var receivedDate = row.querySelector('td:nth-child(8)').textContent.toLowerCase();
        var receivedBy = row.querySelector('td:nth-child(9)').textContent.toLowerCase();

        if (
          area.includes(searchTerm) ||
          name.includes(searchTerm) ||
          folio.includes(searchTerm) ||
          issuerName.includes(searchTerm) ||
          issuerNit.includes(searchTerm) ||
          deliveryDate.includes(searchTerm) ||
          deliveredBy.includes(searchTerm) ||
          receivedDate.includes(searchTerm) ||
          receivedBy.includes(searchTerm)
        ) {
          row.style.display = ''; // Muestra la fila si coincide con el término de búsqueda
        } else {
          row.style.display = 'none'; // Oculta la fila si no coincide
        }
      });
    });
  });
</script>

    </table>
    <!-- Estilos Bootstrap para la paginación -->
<div class="d-flex justify-content-center mt-4 ">
    <ul class="pagination">
        @if ($recibidos->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Anterior</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $recibidos->previousPageUrl() }}" class="page-link" aria-label="Anterior">
                    <span aria-hidden="true">&laquo; Anterior</span>
                </a>
            </li>
        @endif

        @if ($recibidos->hasMorePages())
            <li class="page-item">
                <a href="{{ $recibidos->nextPageUrl() }}" class="page-link" aria-label="Siguiente">
                    <span aria-hidden="true">Siguiente &raquo;</span>
                </a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Siguiente</span>
            </li>
        @endif
    </ul>
</div>
  </div>

  @foreach ($recibidos as $factura)
  <!-- Modal -->
  <div class="modal fade" id="facturaModal{{$factura->id}}" tabindex="-1" role="dialog"
    aria-labelledby="facturaModalLabel{{$factura->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="d-flex flex-column align-items-start">
            <h3 class="modal-title mb-0" id="facturaLabel">Datos de Factura</h3>
            <h6 class="mt-2">{{ $factura->name }}</h6>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
          </button>
        </div>
        <div class="modal-body">
          <ul class="list-unstyled ">
          <li><strong>Nombre:</strong> {{ $factura->name }}</li>
            <li><strong>Folio:</strong> {{ $factura->folio}}</li>
            <li><strong>NIT de Emisor:</strong> {{ $factura->issuer_nit }}</li>
            <li><strong>Nombre de Emisor:</strong> {{ $factura->issuer_name}}</li>
            <li><strong>Prefijo:</strong> {{ $factura->issuer_name}}</li>
            <li><strong>Entregado:</strong> {{ $factura->delivery_date}}</li>
            <li><strong>Entregado Por:</strong> {{ $factura->delivered_by }}</li>
            <li><strong>Recibido :</strong> {{ $factura->received_date }}</li>
            <li><strong>Recibido por:</strong> {{ $factura->received_by }}</li>
            <li><strong>Area</strong> {{ $factura->area }}</li>
            
          </ul>
          <div class="row mt-4">
            <div class="col-12 flex-column"> <!-- Agregado flex-column aquí -->
              <label for="nota">Nota</label>
              <textarea class="form-control mb-2" id="note" name="note" rows="3">{{ $factura->note }}</textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
</div>
    @endforeach
    @endsection

