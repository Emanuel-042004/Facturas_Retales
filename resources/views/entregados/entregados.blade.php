@extends('layouts.header')
@section('content')

<div class="container">
<div class="col-8 mt-4">
  <button type="button" class="btn btn-dark" onclick="window.location.href='{{ url('/index') }}'">Volver</button>
  <h1 class="mt-4">Entregados <img width="48" height="48"
      src="https://img.icons8.com/color/48/000000/in-progress--v1.png" alt="in-progress--v1" /></h1>
  @include('layouts.areas')
  
  <div class="col-md-6 mt-4">
    <input type="text" id="searchInputEntregados" class="form-control" placeholder="Buscar por nombre, folio, etc." />
  </div>
</div>

    <table class="table table-responsive mt-4">
      <thead class="table-dark">
        <tr>
        <th></th>
        <th>Estado</th>
          <th>Area</th>
          <th>Nombre</th>
          <th>Folio</th>
          <th>Nombre de Emisor</th>
          <th>NIT de Emisor</th>
          <th>Entregado</th>
          <th>Entregado por</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($entregados as $factura)
        @if (!$area || $factura->area === $area)
        <tr class="tr-warning tr-entre">
        <td>
          <input type="checkbox" class="form-check-input" name="selectedFacturas[]" value="{{ $factura->id }}">
        </td>
        <td>{{ $factura->status}}</td>
          <td>{{ $factura->area }}</td>
          <td>{{ $factura->name }}</td>
          <td>{{ $factura->folio}}</td>
          <td>{{ $factura->issuer_name}}</td>
          <td>{{ $factura->issuer_nit }}</td>
          <td>{{ $factura->delivery_date}}</td>
          <td>{{ $factura->delivered_by }}</td>
          
          <td class="icon-cell">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill"
              viewBox="0 0 16 16" style="margin-right: 25px; cursor: pointer;"
              onclick="confirmarEliminacion({{ $factura->id }})">
              <path
                d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
            </svg>
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
        // SweetAlert para confirmar la eliminación
        function confirmarEliminacion(id) {
          Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              // Redirigir o ejecutar la acción de eliminación aquí
              window.location.href = '/eliminar-factura/' + id;
            }
          });
        }
      </script>
<!--BUSCADOR-->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    var searchInputEntregados = document.getElementById('searchInputEntregados');

    // Agrega un evento para manejar cambios en el campo de búsqueda para la segunda tabla
    searchInputEntregados.addEventListener('input', function () {
      var searchTerm = searchInputEntregados.value.toLowerCase();

      // Filtra las filas de la segunda tabla según el término de búsqueda
      var rows = document.querySelectorAll('.tr-entre');

      rows.forEach(function (row) {
        var area = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
        var name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
        var folio = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
        var issuerName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
        var issuerNit = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
        var deliveryDate = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
        var deliveredBy = row.querySelector('td:nth-child(7)').textContent.toLowerCase();

        if (
          area.includes(searchTerm) ||
          name.includes(searchTerm) ||
          folio.includes(searchTerm) ||
          issuerName.includes(searchTerm) ||
          issuerNit.includes(searchTerm) ||
          deliveryDate.includes(searchTerm) ||
          deliveredBy.includes(searchTerm)
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
        @if ($entregados->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link">Anterior</span>
            </li>
        @else
            <li class="page-item">
                <a href="{{ $entregados->previousPageUrl() }}" class="page-link" aria-label="Anterior">
                    <span aria-hidden="true">&laquo; Anterior</span>
                </a>
            </li>
        @endif

        @if ($entregados->hasMorePages())
            <li class="page-item">
                <a href="{{ $entregados->nextPageUrl() }}" class="page-link" aria-label="Siguiente">
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
  </div>
 
  @foreach ($entregados as $factura)
  <!-- Modal -->
  <div class="modal fade" id="facturaModal{{$factura->id}}" tabindex="-1" role="dialog"
    aria-labelledby="facturaModalLabel{{$factura->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <div class="d-flex flex-column align-items-start">
            <h3 class="modal-title mb-0" id="facturaLabel">Datos de Factura</h3>
            <h6 class="mt-2">{{ $factura->name }} </h6>
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
            <li><strong>Area</strong> {{ $factura->area }}</li>
          </ul>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" onclick="confirmarRecibido({{ $factura->id }})">Recibir</button>

          </div>
        </div>
      </div>
    </div>
  </div>
  @endforeach
  <script>
  function confirmarRecibido(id) {
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, recibir',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Crear un formulario dinámico
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/recibir-factura/' + id;

        // CSRF Token
        var csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';

        // Campo para indicar el método PUT
        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'POST';

        // Agregar elementos al formulario
        form.appendChild(csrfInput);
        form.appendChild(methodInput);

        // Adjuntar el formulario al cuerpo del documento
        document.body.appendChild(form);

        // Enviar el formulario
        form.submit();
      }
    });
  }
</script>
  @endsection
