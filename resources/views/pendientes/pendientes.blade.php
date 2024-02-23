@extends('layouts.header')

@section('content')


<div class="tablas">
  <div class="cardHeader">
    <h2>Facturas DIAN</h2>
    <!-- Botones ocultos al principio -->
    <div id="botonesContainer" style="display: none;">
      <button id="Reembolso" class="btn" onclick="cambiarTipo('Reembolso')">Reembolso</button>
      <button id="Legalizacion" class="btn" onclick="cambiarTipo('Legalizacion')">Legalizacion</button>
    </div>
    <a href="#" class="btn" onclick="openPopup('facturaPopup')">Factura Manual</a>
  </div><br>
  <div class="search">
    <label>
      <input type="text" placeholder="Buscar" id="searchInput">
      <ion-icon name="search-outline"></ion-icon>
    </label>
  </div>
  <table>
    <thead>
      <td></td>
      <td>Estado</td>
      <td>Tipo</td>
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
        <input type="checkbox" class="form-check-input" name="selectedFacturas[]" value="{{ $factura->id }}"
          onchange="agregarBotones()">
      </td>
      <td><span class="status pending">{{ $factura->status}}</span></td>
      <td>{{ $factura->type }}</td>
      <td>{{ $factura->area }}</td>
      <td>{{ $factura->name }}</td>
      <td>{{ $factura->folio}}</td>
      <td>{{ $factura->issuer_name}}</td>
      <td>{{ $factura->issuer_nit }}</td>
      <td>

        <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaPopup{{$factura->id}}')"></ion-icon>
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
<script>
  document.getElementById('searchInput').addEventListener('keyup', function () {
    let value = this.value;
    filterTable(value);
  });

  function filterTable(value) {
    let rows = document.querySelectorAll('tbody tr');
    rows.forEach(row => {
      let text = row.textContent.toLowerCase();
      if (text.includes(value.toLowerCase())) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  }
  function agregarBotones() {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    var botonesContainer = document.getElementById('botonesContainer');

    // Mostrar o ocultar los botones dependiendo de si hay checkboxes seleccionados
    if (checkboxes.length > 0) {
      botonesContainer.style.display = 'block';
    } else {
      botonesContainer.style.display = 'none';
    }
  }
</script>

<script>
  function cambiarTipo(tipo) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    var ids = [];
    checkboxes.forEach(function (checkbox) {
      ids.push(checkbox.value);
    });

    if (ids.length > 0) {
      axios.post('{{ route("cambiar_tipo_facturas") }}', {
        tipo: tipo,
        ids: ids
      })
        .then(function (response) {
          location.reload(); // Recarga la página después de que se compsolicitud
        })
        .catch(function (error) {
          console.error(error);
        });
    } else {
      alert('Por favor selecciona al menos una factura.');
    }
  }
</script>



<!-- ================ FACTURA MANUAL ================= -->
<div class="popup-background" id="popupBackground"></div>

<div class="popup" id="facturaPopup">
  <div class="popup-content">
    <div class="header">
      <h2 class="modal-title">Datos de Factura</h2>
      <span class="close-icon" onclick="closePopup('facturaPopup')">&times;</span>

    </div>
    <form id="crearfactura" action="{{ route('facturas.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="name" name="name" placeholder="Nombre">
        </div>
        <div class="form-group col-md-6">
          <label for="folio">Folio</label>
          <input type="text" class="form-control" id="folio" name="folio" placeholder="Contrato">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="issuer_name">Nombre Emisor</label>
          <input type="text" class="form-control" id="issuer_name" name="issuer_name">
        </div>
        <div class="form-group col-md-6">
          <label for="issuer_nit">Nit Emisor</label>
          <input type="text" class="form-control" id="issuer_nit" name="issuer_nit">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="prefix">Prefijo</label>
          <input type="text" class="form-control" id="prefix" name="prefix">
        </div>
        <div class="form-group col-md-6">
          <label for="area">Área</label>
          <select class="form-control" id="area" name="area">
            <option value="">Selecciona</option>
            <option value="Compras">Compras</option>
            <option value="Financiera">Financiera</option>
            <option value="Logistica">Logística</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Tecnologia">Tecnología</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="pdf1">ANEXO 1</label>
          <div class="file-drop-area">
            <input type="file" class="form-control-file" id="pdf1" name="pdf1">
            <span class="file-msg">Arrastra y suelta aquí o haz clic para seleccionar un archivo</span>
          </div>
        </div>
        <div class="form-group col-md-6">
          <label for="pdf2">ANEXO 2</label>
          <div class="file-drop-area">
            <input type="file" class="form-control-file" id="pdf2" name="pdf2">
            <span class="file-msg">Arrastra y suelta aquí o haz clic para seleccionar un archivo</span>
          </div>
        </div>

        <div class="form-group col-md-6">
          <label for="note">Nota</label>
          <textarea class="form-control" id="note" name="note"></textarea>
        </div>
      </div>
      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Crear</button>
      </div>
    </form>
  </div>
</div>




<!-- ================ ACCIONES ================= -->
<div class="popup-background" id="popupBackground"></div>
@foreach ($pendientes as $factura)
<div class="popup" id="facturaPopup{{$factura->id}}">
  <div class="popup-content">
    <div class="header">
      <h2 class="modal-title">Datos de Factura</h2>
      <span class="close-icon" onclick="closePopup('facturaPopup{{$factura->id}}')">&times;</span>
    </div>
    <form id="cargarFacturaForm{{$factura->id}}" action="{{ route('cargar_factura', ['id' => $factura->id]) }}"
      method="POST" enctype="multipart/form-data">
      @csrf

      <div class="form-group col-md-6">
        <label for="type">Tipo</label>
        <select class="form-control" id="type" name="type">
          <option value="">Selecciona</option>
          <option value="Factura electrónica" @selected( "Factura electrónica"==$factura -> type)>Factura electrónica
          </option>
          <option value="Nota de crédito electrónica" @selected( "Nota de crédito electrónica"==$factura ->
            type)>Financiera</option>
          <option value="Reembolso" @selected( "Reembolso"==$factura -> type)>Reembolso</option>
          <option value="Legalizacion" @selected( "Legalizacion"==$factura -> type) >Legalizacion</option>

        </select>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="name" name="name" value="{{$factura->name}}" placeholder="Nombre">
        </div>
        <div class="form-group col-md-6">
          <label for="folio">Folio</label>
          <input type="text" class="form-control" id="folio" name="folio" value="{{$factura->folio}}"
            placeholder="Contrato">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="issuer_name">Nombre Emisor</label>
          <input type="text" class="form-control" id="issuer_name" name="issuer_name" value="{{$factura->issuer_name}}">
        </div>
        <div class="form-group col-md-6">
          <label for="issuer_nit">Nit Emisor</label>
          <input type="text" class="form-control" id="issuer_nit" name="issuer_nit" value="{{$factura->issuer_nit}}">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="prefix">Prefijo</label>
          <input type="text" class="form-control" id="prefix" name="prefix" value="{{$factura->prefix}}">
        </div>
        <div class="form-group col-md-6">
          <label for="area">Área</label>
          <select class="form-control" id="area" name="area">
            <option value="">Selecciona</option>
            <option value="Compras" @selected( "Compras"==$factura -> area)>Compras</option>
            <option value="Financiera" @selected( "Financiera"==$factura -> area)>Financiera</option>
            <option value="Logistica" @selected( "Logistica"==$factura -> area)>Logística</option>
            <option value="Mantenimiento" @selected( "Mantenimiento"==$factura -> area) >Mantenimiento</option>
            <option value="Tecnologia" @selected( "Tecnologia"==$factura -> area)>Tecnología</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="anexos">ANEXOS</label>
          <div class="file-drop-area">
            <input type="file" class="form-control-file" id="anexos" name="anexos[]" multiple>
          </div>
        </div>
      </div>
      <div class="form-group col-md-6">
        <label for="note">Nota</label>
        <textarea class="form-control" id="note" name="note"></textarea>
      </div>
      <div class="modal-footer">
        <!-- Botón de "cargar" -->
        <button type="button" id="cargarBtn{{$factura->id}}" class="btn btn-primary"
          onclick="confirmarEntrega('cargarFacturaForm{{$factura->id}}', '{{$factura->id}}')">cargar</button>
        <!-- Elemento para la animación de carga -->
        <div id="loading{{$factura->id}}" style="display: none;">
          <div class="loading-icon"></div>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach
<script>
  function toggleAnexo(anexoId, show) {
    var anexo = document.getElementById(anexoId);
    var btnPlus = anexo.previousElementSibling.querySelector('button:first-child');
    var btnMinus = anexo.previousElementSibling.querySelector('button:last-child');

    if (show) {
      anexo.style.display = "block";
      btnPlus.disabled = true;
      btnMinus.disabled = false;
    } else {
      anexo.style.display = "none";
      btnPlus.disabled = false;
      btnMinus.disabled = true;
    }
  }
</script>

<!-- ================ Estilos para la animación de carga ================= -->
<style>
  .loading-icon {
    /* Estilos para la animación de carga */
    border: 6px solid #f3f3f3;
    border-top: 6px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>

<script>
  function confirmarEntrega(formId, facturaId) {
    // Mostrar una alerta de confirmación del navegador
    var confirmacion = confirm("¿Estás seguro de que deseas cargar la factura?");
    // Si el usuario hace clic en "Aceptar", ocultar el botón de "cargar" y mostrar la animación de carga
    if (confirmacion) {
      document.getElementById(formId).submit();
      document.getElementById('cargarBtn' + facturaId).style.display = "none"; // Ocultar el botón de "cargar"
      document.getElementById('loading' + facturaId).style.display = "block"; // Mostrar laón de carga
    }
  }
</script>



<!-- ================ Abrir PopUp ================= -->
<script>
  function openPopup(popupId) {
    document.getElementById(popupId).style.display = "block";
    document.getElementById('popupBackground').style.display = "block"; // Mostrar el fondo gris
  }

  function closePopup(popupId) {
    document.getElementById(popupId).style.display = "none";
    document.getElementById('popupBackground').style.display = "none"; // Ocultar el fondo gris
  }
</script>

@endsection