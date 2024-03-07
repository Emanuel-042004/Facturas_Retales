@extends('layouts.header')

@section('content')

<div class="tablas">
    <h1>Reembolsos</h1><br>

    @if ($reembolsos->isEmpty())
    <p>No hay reembolsos registrados.</p>
    @else
    <div class="reembolsos-container">
        @foreach ($reembolsos as $reembolso)
        <div class="reembolso-card">
            <div class="reembolso-header">
                <h2 class="reembolso-title">Reembolso {{ $reembolso->consecutivo }}</h2>
            </div>
            <div class="reembolso-body" id="facturas_{{ $reembolso->id }}" style="display: none;">
                <h3 class="reembolso-subtitle">Facturas</h3>
                <table class="facturas-table">
                    <thead>
                        <tr><td>Estado</td>
                            <td>Proceso</td>
                            <th>Tipo</th>
                            <th>Area</th>
                            <th>Folio</th>
                            <th>Nombre de Emisor</th>
                            <th>NIT de Emisor</th>
                            <th>Fecha de Emision</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reembolso->facturas as $factura)
                        <tr>
                            <td>{{ $factura->status }}</td>
                            <td><span class="status loaded1">{{ $factura->subtype }}</span></td>
                            <td>{{ $factura->type }}</td>
                            <td>{{ $factura->area }}</td>
                            <td>{{ $factura->folio }}</td>
                            <td>{{ $factura->issuer_name }}</td>
                            <td>{{ $factura->issuer_nit }}</td>
                            <td>{{ $factura->issue_date }}</td>
                            <td><ion-icon name="ellipsis-vertical-outline"
          onclick="openPopup('facturaAdjuntadaPopup{{$factura->id}}')"></ion-icon></td>
                           
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">No hay facturas asociadas a este reembolso.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="reembolso-footer">
                <button class="reembolso-button" onclick="toggleFacturas({{ $reembolso->id }})">Ver Facturas</button>
            </div>
        </div>
        <!-- ================ ADJUNTADAS ================= -->
<div class="popup-background" id="popupBackground"></div>
@foreach ($reembolso->facturas as $factura)
<div class="popup" id="facturaAdjuntadaPopup{{$factura->id}}">
  <div class="popup-content">
    <div class="header">
      <h2 class="modal-title">Datos de Factura</h2>
      <span class="close-icon" onclick="closePopup('facturaAdjuntadaPopup{{$factura->id}}')">&times;</span>
    </div>
    <form id="aprobarFacturaForm{{$factura->id}}" action=""
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
          <label for="anexos">Archivos Adjuntos</label>
          <div class="attachment-box">
            <ul class="no-bullet">
              @if($factura->anexo1)
              <li>
              <input type="checkbox" name="documentos_malos[]">
                <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo1) }}')">
                  <i class="fas fa-file"></i> Anexo 1 - {{ $factura->anexo1 }}</button>
              </li>
              @endif
              @if($factura->anexo2)
              <li>
                <input type="checkbox" name="documentos_malos[]">
                <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo2) }}')">
                  <i class="fas fa-file"></i>
                  Anexo 2 - {{ $factura->anexo2 }}</button>
              </li>
              @endif
              @if($factura->anexo3)
              <li>
                <input type="checkbox" name="documentos_malos[]">
                <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo3) }}')">
                  <i class="fas fa-file"></i>
                  Anexo 3 - {{ $factura->anexo3 }}</button>
              </li>
              @endif
              @if($factura->anexo4)
              <li>
                <input type="checkbox" name="documentos_malos[]">
                <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo4) }}')">
                  <i class="fas fa-file"></i>
                  Anexo 4 - {{ $factura->anexo4 }}</button>
              </li>
              @endif
              @if($factura->anexo5)
              <li>
                <input type="checkbox" name="documentos_malos[]">
                <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo5) }}')">
                  <i class="fas fa-file"></i>
                  Anexo 5 - {{ $factura->anexo5 }}</button>
              </li>
              @endif
              @if($factura->anexo6)
              <li>
                <input type="checkbox" name="documentos_malos[]">
                <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo6) }}')">
                  <i class="fas fa-file"></i>
                  Anexo 6 - {{ $factura->anexo6 }}</button>
              </li>
              @endif

            </ul>
          </div>
        </div>
      </div>
      <div class="form-group col-md-6">
        <label for="note">Nota</label>
        <textarea class="form-control" id="note" name="note">{{$factura->note}}</textarea>
      </div>
      <div class="modal-footer">
        <a href="{{route('pendientes.rechazar', ['id' => $factura->id])}}" class="btn btn-danger">Rechazar</a>
        <button type="submit" class="btn btn-primary">Aprobar</button>
      </div>
    </form>
  </div>
</div>
@endforeach
        @endforeach
    </div>
    @endif

    <div>
    <ul class="pagination">
      <li class="{{ $reembolsos->onFirstPage() ? 'disabled' : '' }}">
        <a href="{{ $reembolsos->previousPageUrl() }}" aria-label="Anterior">
          <span aria-hidden="true">« Anterior</span>
        </a>
      </li>

      <li class="{{ $reembolsos->hasMorePages() ? '' : 'disabled' }}">
        <a href="{{ $reembolsos->nextPageUrl() }}" class="page-link" aria-label="Siguiente">
          <span aria-hidden="true">Siguiente »</span>
        </a>
      </li>
    </ul>
  </div>
</div>

<script>
    function toggleFacturas(reembolsoId) {
        var facturasDiv = document.getElementById('facturas_' + reembolsoId);
        if (facturasDiv.style.display === 'none') {
            facturasDiv.style.display = 'block';
        } else {
            facturasDiv.style.display = 'none';
        }
    }
</script>


<script>
  function openDocument(url) {
    event.preventDefault(); // Evitar que el formulario se envíe

    const popup = document.createElement('div');
    popup.id = 'documentoPopup'; // Asignar un ID al popup del documento
    popup.classList.add('popup');
    popup.style.display = 'block';

    const popupContent = document.createElement('div');
    popupContent.classList.add('popup-content');

    const header = document.createElement('div');
    header.classList.add('header');
    header.style.position = 'fixed'; // Fijar el encabezado
    header.style.top = '0'; // Fijar en la parte superior
    header.style.left = '50%'; // Centrar horizontalmente
    header.style.transform = 'translateX(-50%)'; // Centrar horizontalmente
    header.style.width = '100%'; // Ancho del 90%
    header.style.backgroundColor = '#ffffff'; // Fondo blanco
    header.style.padding = '10px'; // Agregar relleno
    header.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.2)'; // Sombra
    header.style.zIndex = '9998'; // Asegurar que esté por encima de otros elementos

    const title = document.createElement('h2');
    title.classList.add('modal-title');
    title.textContent = 'Anexos';
    title.style.margin = '0'; // Eliminar el margen superior del título

    const closeButton = document.createElement('span');
    closeButton.classList.add('close-icon');
    closeButton.textContent = '×'; // Usar el carácter de multiplicación como icono de cierre
    closeButton.style.cursor = 'pointer'; // Cambiar el cursor al pasar sobre el botón
    closeButton.style.position = 'absolute'; // Posición absoluta
    closeButton.style.top = '5px'; // Ajustar distancia desde la parte superior
    closeButton.style.right = '5px'; // Ajustar distancia desde la derecha
    closeButton.onclick = closeDocumentPopup; // Asignar la función de cierre al hacer clic

    header.appendChild(title);
    header.appendChild(closeButton);

    const iframe = document.createElement('iframe');
    iframe.src = url;
    iframe.width = '1000px'; // Ancho completo
    iframe.height = '1020px'; // Altura menos la altura del encabezado y un poco de margen
    iframe.frameBorder = '0';

    popupContent.appendChild(header);
    popupContent.appendChild(iframe);
    popup.appendChild(popupContent);

    document.body.appendChild(popup);

    document.getElementById('popupBackground').style.display = 'block'; // Mostrar el fondo gris
  }
  function closeDocumentPopup() {
    const popup = document.querySelector('#documentoPopup');
    if (popup) {
      popup.remove(); // Eliminar el popup del DOM
    }
    // No ocultar el fondo gris aquí, ya que el popupBackground debe mantenerse visible
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
