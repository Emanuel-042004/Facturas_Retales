@extends('layouts.header')

@section('content')

<div class="tablas">
  <div class="cardHeader">
    <h2>Facturas DIAN</h2>
    <a href="#" class="btn" onclick="openPopup('facturaPopup')">Factura Manual</a>
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
      <td><span class="status pending">{{ $factura->status}}</span></td>
      <td>{{ $factura->area }}</td>
      <td>{{ $factura->name }}</td>
      <td>{{ $factura->folio}}</td>
      <td>{{ $factura->issuer_name}}</td>
      <td>{{ $factura->issuer_nit }}</td>
      <td><ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaPopup{{$factura->id}}')" ></ion-icon></td>
      

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

<!-- ================ FACTURA MANUAL ================= -->
<div class="popup-background" id="popupBackground"></div>

<div class="popup" id="facturaPopup">
  <div class="popup-content">
    <div class="header">
      <h2 class="modal-title">Datos de Factura</h2>
      <span class="close-icon" onclick="closePopup('facturaPopup')">&times;</span>
      
    </div>
    <form id="crearfactura" action="{{ route('facturas.store') }}"
      method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="name" name="name"  placeholder="Nombre"
          >
        </div>
        <div class="form-group col-md-6">
          <label for="folio">Folio</label>
          <input type="text" class="form-control" id="folio" name="folio" 
            placeholder="Contrato">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="issuer_name">Nombre Emisor</label>
          <input type="text" class="form-control" id="issuer_name" name="issuer_name" 
          >
        </div>
        <div class="form-group col-md-6">
          <label for="issuer_nit">Nit Emisor</label>
          <input type="text" class="form-control" id="issuer_nit" name="issuer_nit" 
          >
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="prefix">Prefijo</label>
          <input type="text" class="form-control" id="prefix" name="prefix" >
        </div>
        <div class="form-group col-md-6">
          <label for="area">Área</label>
          <select class="form-control" id="area" name="area" >
            <option value="">Selecciona</option>
            <option value="Compras" >Compras</option>
            <option value="Financiera" >Financiera</option>
            <option value="Logistica" >Logística</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Tecnologia">Tecnología</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="pdf1">ANEXO 1</label>
          <div class="file-drop-area">
            <input type="file" class="form-control-file" id="pdf1" name="pdf1" >
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
      </div>
      <div class="modal-footer">
        
        <button type="submit" class="btn btn-primary">Crear</button>
      </div>
    </form>
  </div>
</div>




<!-- ================ Ventana Emergente ================= -->
<div class="popup-background" id="popupBackground"></div>
@foreach ($pendientes as $factura)
<div class="popup" id="facturaPopup{{$factura->id}}">
  <div class="popup-content">
    <div class="header">
      <h2 class="modal-title">Datos de Factura</h2>
      <span class="close-icon" onclick="closePopup('facturaPopup{{$factura->id}}')">&times;</span>
    </div>
    <form id="entregarFacturaForm{{$factura->id}}" action="{{ route('entregar_factura', ['id' => $factura->id]) }}"
      method="POST" enctype="multipart/form-data">
      @csrf
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="nombre">Nombre</label>
          <input type="text" class="form-control" id="name" name="name" value="{{$factura->name}}" placeholder="Nombre"
            required>
        </div>
        <div class="form-group col-md-6">
          <label for="folio">Folio</label>
          <input type="text" class="form-control" id="folio" name="folio" value="{{$factura->folio}}"
            placeholder="Contrato" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="issuer_name">Nombre Emisor</label>
          <input type="text" class="form-control" id="issuer_name" name="issuer_name" value="{{$factura->issuer_name}}"
            required>
        </div>
        <div class="form-group col-md-6">
          <label for="issuer_nit">Nit Emisor</label>
          <input type="text" class="form-control" id="issuer_nit" name="issuer_nit" value="{{$factura->issuer_nit}}"
            required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="prefix">Prefijo</label>
          <input type="text" class="form-control" id="prefix" name="prefix" value="{{$factura->prefix}}" required>
        </div>
        <div class="form-group col-md-6">
          <label for="area">Área</label>
          <select class="form-control" id="area" name="area" required>
            <option value="">Selecciona</option>
            <option value="Compras" @if("Compras"==$factura->area) selected @endif>Compras</option>
            <option value="Financiera" @if("Financiera"==$factura->area) selected @endif>Financiera</option>
            <option value="Logistica" @if("Logistica"==$factura->area) selected @endif>Logística</option>
            <option value="Mantenimiento" @if("Mantenimiento"==$factura->area) selected @endif>Mantenimiento</option>
            <option value="Tecnologia" @if("Tecnologia"==$factura->area) selected @endif>Tecnología</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="pdf1">ANEXO 1</label>
          <div class="file-drop-area">
            <input type="file" class="form-control-file" id="pdf1" name="pdf1" required>
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger"
          onclick="asignarArea({{ $factura->id }}, '{{ $factura->area }}')">Asignar a mi área</button>
        <button type="submit" class="btn btn-primary">Entregar</button>
      </div>
    </form>
  </div>
</div>
@endforeach




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