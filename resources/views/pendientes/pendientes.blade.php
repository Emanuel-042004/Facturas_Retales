@extends('layouts.header')

@section('content')

@if (Session::has('success'))
<script>
  Swal.fire({
    title: '¡Factura Entregada!',
    text: '{{ Session::get('success') }}',
    icon: 'success',
    timer: 2000
  }).then(() => {
    window.location.replace('{{ route('pendientes.index') }}');
  });
</script>
@endif

@if (Session::has('asignar'))
<script>
  Swal.fire({
    title: '¡Factura Asignada!',
    text: '{{ Session::get('asignar') }}',
    icon: 'success',
    timer: 2000
  }).then(() => {
    window.location.replace('{{ route('pendientes.index') }}');
  });
</script>
@endif

@if (Session::has('destroy'))
<script>
  Swal.fire({
    title: '¡Factura Eliminada!',
    text: '{{ Session::get('destroy') }}',
    icon: 'success',
    timer: 2000
  }).then(() => {
    window.location.replace('{{ route('pendientes.index') }}');
  });
</script>
@endif
<br><br><br>
<div class="container">
  <div class="col-8 mt-4|">
    <button type="button" class="btn btn-dark" onclick="window.location.href='{{ url('/index') }}'">Volver</button>
    <h1 class="mt-4">Pendientes <img width="48" height="48" src="https://img.icons8.com/parakeet/48/time-machine.png"
        alt="time-machine" /></h1>
    @include('layouts.areas')
    </ul>

    <div class="col-md-6 mt-4">
      <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre, folio, etc." />
    </div>
  </div>
  <!-- Botones para acciones -->
  <div class="mt-3" id="acciones-container" style="display: none;">
    <a href="{{route('eliminar_seleccion')}}" id="eliminarSeleccionados" onclick="confirmarEliminacionSeleccion()"
      class="btn btn-danger">Eliminar seleccionados</a>
  </div>

  <table class="table table-responsive mt-4  ">
    <thead class="table-dark">
      <tr>
        <th></th>
        <th>Estado</th>
        <th>Area</th>
        <th>Nombre</th>
        <th>Folio</th>
        <th>Nombre de Emisor</th>
        <th>NIT de Emisor</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($pendientes as $factura)
      @if (!$area || $factura->area === $area)
      <tr class="tr-danger tr-entre">
        <td>
          <input type="checkbox" class="form-check-input" name="selectedFacturas[]" value="{{ $factura->id }}">
        </td>
        <td>{{ $factura->status}}</td>
        <td>{{ $factura->area }}</td>
        <td>{{ $factura->name }}</td>
        <td>{{ $factura->folio}}</td>
        <td>{{ $factura->issuer_name}}</td>
        <td>{{ $factura->issuer_nit }}</td>
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
      // Función para confirmar la eliminación y enviar los IDs de las facturas seleccionadas al controlador
      function confirmarEliminacionSeleccion() {
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
            // Obtener los IDs de las facturas seleccionadas
            var selectedFacturas = [];
            var checkboxes = document.querySelectorAll('input[name="selectedFacturas[]"]:checked');
            checkboxes.forEach(function (checkbox) {
              selectedFacturas.push(checkbox.value);
            });

            // Enviar los IDs al controlador mediante AJAX
            fetch('/realizar-acciones', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}', // Asegúrate de que esta variable esté disponible en tu plantilla Blade
              },
              body: JSON.stringify({
                selectedFacturas: selectedFacturas,
                action: 'delete'
              })
            })
              .then(response => {
                // Recargar la página después de eliminar las facturas
                window.location.reload();
              })
              .catch(error => {
                console.error('Error al eliminar las facturas:', error);
              });
          }
        });
      }
    </script>

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
        var currentPath = window.location.hash;
        var navLinks = document.querySelectorAll('.nav-underline .nav-link');
        var searchInput = document.getElementById('searchInput');

        navLinks.forEach(function (link) {
          if (link.getAttribute('href') === currentPath) {
            link.parentNode.classList.add('active');
          }
        });

        // Agrega un evento para manejar cambios en el campo de búsqueda
        searchInput.addEventListener('input', function () {
          var searchTerm = searchInput.value.toLowerCase();

          // Filtra las filas de la tabla según el término de búsqueda
          var rows = document.querySelectorAll('.tr-entre');

          rows.forEach(function (row) {
            var area = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
            var name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            var folio = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            var issuerName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
            var issuerNit = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

            if (
              area.includes(searchTerm) ||
              name.includes(searchTerm) ||
              folio.includes(searchTerm) ||
              issuerName.includes(searchTerm) ||
              issuerNit.includes(searchTerm)
            ) {
              row.style.display = ''; // Muestra la fila si coincide con el término de búsqueda
            } else {
              row.style.display = 'none'; // Oculta la fila si no coincide
            }
          });
        });
      });
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        var selectedFacturas = [];

        // Evento de cambio en el checkbox
        document.addEventListener('change', function (event) {
          if (event.target.classList.contains('form-check-input')) {
            var facturaId = event.target.value;
            if (event.target.checked) {
              // Agregar factura a la lista de seleccionadas
              selectedFacturas.push(facturaId);
            } else {
              // Eliminar factura de la lista de seleccionadas
              var index = selectedFacturas.indexOf(facturaId);
              if (index !== -1) {
                selectedFacturas.splice(index, 1);
              }
            }

            // Mostrar u ocultar botones según si hay facturas seleccionadas
            var accionesContainer = document.getElementById('acciones-container');
            accionesContainer.style.display = selectedFacturas.length > 0 ? 'block' : 'none';
          }
        });

        // Evento de clic en el enlace de eliminar seleccionados
        document.getElementById('eliminarSeleccionados').addEventListener('click', function (event) {
          event.preventDefault(); // Evita que el enlace navegue a otra página
          realizarAccion('delete');
        });

        // Función para enviar la acción al controlador mediante AJAX
        function realizarAccion(action) {
          if (selectedFacturas.length > 0) {
            // Envía la solicitud AJAX al controlador
            fetch('{{ route('eliminar_seleccion') }}', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
              },
              body: JSON.stringify({
                selectedFacturas: selectedFacturas,
                action: action,
              }),
            })
              .then(response => response.json())
              .then(data => {
                // Maneja la respuesta del controlador según tus necesidades
                console.log(data);
                // Actualiza la interfaz o realiza otras acciones necesarias
                window.location.reload(); // Recarga la página después de eliminar
              })
              .catch(error => {
                console.error('Error al realizar la acción:', error);
              });
          }
        }
      });
    </script>
  </table>

  <!-- Estilos Bootstrap para la paginación -->
  <div class="d-flex justify-content-center mt-4 ">
    <ul class="pagination">
      @if ($pendientes->onFirstPage())
      <li class="page-item disabled">
        <span class="page-link">Anterior</span>
      </li>
      @else
      <li class="page-item">
        <a href="{{ $pendientes->previousPageUrl() }}" class="page-link" aria-label="Anterior">
          <span aria-hidden="true">&laquo; Anterior</span>
        </a>
      </li>
      @endif

      @if ($pendientes->hasMorePages())
      <li class="page-item">
        <a href="{{ $pendientes->nextPageUrl() }}" class="page-link" aria-label="Siguiente">
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

<!-- MODAL -->
@foreach ($pendientes as $factura)
<div class="modal fade" id="facturaModal{{$factura->id}}" tabindex="-1" role="dialog" aria-labelledby="facturaModalLabel{{$factura->id}}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-xl" role="document"> <!-- Cambiado modal-xl para hacerlo amplio -->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="facturaLabel">Datos de Factura</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="entregarFacturaForm{{$factura->id}}" action="{{ route('entregar_factura', ['id' => $factura->id]) }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-lg-6"> <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="nombre">Nombre</label>
              <input type="text" class="form-control mb-2" id="name" name="name" value="{{$factura->name}}" placeholder="Nombre">
            </div>
            <div class="col-lg-6"> <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="folio">Folio</label>
              <input type="text" class="form-control mb-2" id="folio" name="folio" value="{{$factura->folio}}" placeholder="Contrato">
            </div>
          </div>
          <!-- Resto del formulario -->
          <div class="row">
            <div class="col-lg-6"> <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="issuer_name">Nombre Emisor</label>
              <input type="text" class="form-control mb-2" id="issuer_name" name="issuer_name" value="{{$factura->issuer_name}}">
            </div>
            <div class="col-lg-6"> <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="issuer_nit">Nit Emisor</label>
              <input type="text" class="form-control mb-2" id="issuer_nit" name="issuer_nit" value="{{$factura->issuer_nit}}">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6"> <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
              <label for="prefix">Prefijo</label>
              <input type="text" class="form-control mb-2" id="prefix" name="prefix" value="{{$factura->prefix}}">
            </div>
            <div class="col-lg-6"> <!-- Cambiado col-12 a col-lg-6 para ocupar la mitad del espacio en pantallas grandes -->
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
        <button type="button" class="btn btn-danger" onclick="asignarArea({{ $factura->id }}, '{{ $factura->area }}')">Asignar a mi área</button>
        <button type="submit" class="btn btn-primary">Entregar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    document.getElementById('pdf1').addEventListener('change', function(e) {
      var fileName = e.target.files[0].name;
      var fileMsg = this.nextElementSibling;
      fileMsg.textContent = fileName;
      fileMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder2-open" viewBox="0 0 16 16"><path d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v.64c.57.265.94.876.856 1.546l-.64 5.124A2.5 2.5 0 0 1 12.733 15H3.266a2.5 2.5 0 0 1-2.481-2.19l-.64-5.124A1.5 1.5 0 0 1 1 6.14zM2 6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174-1.154C6.374 3.334 5.82 3 5.264 3H2.5a.5.5 0 0 0-.5.5zm-.367 1a.5.5 0 0 0-.496.562l.64 5.124A1.5 1.5 0 0 0 3.266 14h9.468a1.5 1.5 0 0 0 1.489-1.314l.64-5.124A.5.5 0 0 0 14.367 7z"/></svg> ' + fileName;
    });

    document.getElementById('pdf2').addEventListener('change', function(e) {
      var fileName = e.target.files[0].name;
      var fileMsg = this.nextElementSibling;
      fileMsg.textContent = fileName;
      fileMsg.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder2-open" viewBox="0 0 16 16"><path d="M1 3.5A1.5 1.5 0 0 1 2.5 2h2.764c.958 0 1.76.56 2.311 1.184C7.985 3.648 8.48 4 9 4h4.5A1.5 1.5 0 0 1 15 5.5v.64c.57.265.94.876.856 1.546l-.64 5.124A2.5 2.5 0 0 1 12.733 15H3.266a2.5 2.5 0 0 1-2.481-2.19l-.64-5.124A1.5 1.5 0 0 1 1 6.14zM2 6h12v-.5a.5.5 0 0 0-.5-.5H9c-.964 0-1.71-.629-2.174-1.154C6.374 3.334 5.82 3 5.264 3H2.5a.5.5 0 0 0-.5.5zm-.367 1a.5.5 0 0 0-.496.562l.64 5.124A1.5 1.5 0 0 0 3.266 14h9.468a1.5 1.5 0 0 0 1.489-1.314l.64-5.124A.5.5 0 0 0 14.367 7z"/></svg> ' + fileName;
    });
  </script>
@endforeach

<!-- En tu archivo Blade -->
<script>
  function asignarArea(id, area) {
    Swal.fire({
      title: '¿Deseas asignar esta factura a tu área?',
      text: 'Esta acción no se puede deshacer',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Sí, asignar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        // Aquí puedes realizar la acción de asignación
        window.location.href = '/asignar_area/' + id;
      }
    });
  }
</script>

<script>
  function entregarFactura(id) {
    $.ajax({
      type: 'POST',
      url: $('#entregarFacturaForm' + id).attr('action'),
      data: new FormData($('#entregarFacturaForm' + id)[0]),
      contentType: false,
      processData: false,
      success: function (response) {
        console.log(response);
        // Puedes realizar acciones adicionales después de la entrega exitosa
      },
      error: function (error) {
        console.log(error);
        // Maneja el error según tus necesidades
      }
    });
  }
</script>


@endsection