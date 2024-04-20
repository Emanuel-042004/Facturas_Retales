@extends('layouts.header')

@section('content')


<div class="tablas">
  <div class="cardHeader">
    <h2>Facturas Cargadas</h2>
    
  </div>
  <div class="search">
    <form action="{{ route('cargados.index') }}" method="GET">
    

      <div class="search">
    <label>  
      <input type="text" name="q" placeholder="Buscar" id="searchInput">
      <ion-icon name="search-outline"></ion-icon>
      <button type="submit">
       
       </button>

    </label>
  </div>
    </form>
  </div>
  <table>
    <thead>
      <td></td>
      <td>Estado</td>
      <td>Proceso</td>
      <td>Area</td>
      <td>Prefijo</td>
      <td>Folio</td>
      <td>Nombre de Emisor</td>
      <td>NIT de Emisor</td>
      <td>Acciones</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($cargados as $factura)
      @if (!$area || $factura->area === $area)
      <td>
        <input type="checkbox" class="form-check-input" name="selectedFacturas[]" value="{{ $factura->id }}" onchange ="agregarBotones()">
      </td>
      <td><span class="status loaded">{{ $factura->status}}</span></td>
      <td><span class="status approved">{{ $factura->subtype }}</span></td>
      <td>{{ $factura->area }}</td>
      <td>{{ $factura->prefix }}</td>
      <td>{{ $factura->folio}}</td>
      <td>{{ $factura->issuer_name}}</td>
      <td>{{ $factura->issuer_nit }}</td>
      <td>
      
          @if ($factura->subtype == 'Adjuntada')
         <ion-icon name="ellipsis-vertical-outline"
           onclick="openPopup('facturaAdjuntadaPopup{{$factura->id}}')"></ion-icon>
         @elseif($factura->subtype == 'Aprobada')
         <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaAprobadaPopup{{$factura->id}}')"></ion-icon>
         @else
         <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaPopup{{$factura->id}}')"></ion-icon>
         @endif
                           
        
      </td>
      
      </tr>
      @endif
      @endforeach
    </tbody>
  </table>
  <!-- Estilos Bootstrap para la paginación -->
  <div>
    <ul class="pagination">
      <li class="{{ $cargados->onFirstPage() ? 'disabled' : '' }}">
        <a href="{{ $cargados->previousPageUrl() }}" aria-label="Anterior">
          <span aria-hidden="true">« Anterior</span>
        </a>
      </li>

      <li class="{{ $cargados->hasMorePages() ? '' : 'disabled' }}">
        <a href="{{ $cargados->nextPageUrl() }}" class="page-link" aria-label="Siguiente">
          <span aria-hidden="true">Siguiente »</span>
        </a>
      </li>
    </ul>
  </div>
</div>
<script>
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
    header.style.zIndex = '9999'; // Asegurar que esté por encima de otros elementos

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
    iframe.width = '800px'; // Ancho completo
    iframe.height = '900px'; // Altura menos la altura del encabezado y un poco de margen
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

<!-- ================ APROBADAS ================= -->
<div class="popup-background" id="popupBackground"></div>
      @foreach ($cargados as $factura)
      <div class="popup" id="facturaAdjuntadaPopup{{$factura->id}}">
        <div class="popup-content">
          <div class="header">
            <h2 class="modal-title">Datos de Factura</h2>
            <span class="close-icon" onclick="closePopup('facturaAdjuntadaPopup{{$factura->id}}')">&times;</span>
          </div>
          <form id="causarFacturaForm{{$factura->id}}" action="{{ route('cargados_aprobar', ['id' => $factura->id]) }}"
            method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group col-md-6">
        <label for="type">Tipo</label>
        <select class="form-control" id="type" name="type">
          <option value="">Selecciona</option>
          <option value="Factura electrónica" @selected( "Factura electrónica"==$factura -> type)>Factura electrónica
          </option>
          <option value="Documento soporte" @selected( "Documento soporte"==$factura ->type)>Documento soporte</option>
          <option value="Reembolso" @selected( "Reembolso"==$factura ->type)>Reembolso</option>
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
                <label for="cude">CUFE</label>
                <div class="input-group">
                  <textarea class="form-control" id="cude" name="cude">{{$factura->cude}}</textarea>
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" onclick="buscarCUFE()">
                      <ion-icon name="search-outline"></ion-icon>
                    </button>
                  </div>
                </div>
              </div>
              <div class="form-group col-md-6">
                <label for="area">Área</label>
                <select class="form-control" id="area" name="area">
                  <option value="">Selecciona</option>
                  <option value="Compras" @selected( "Compras"==$factura -> area)>Compras</option>
                  <option value="Financiera" @selected( "Financiera"==$factura -> area)>Financiera</option>
                  <option value="Logistica" @selected( "Logistica"==$factura -> area)>Logística</option>
                  <option value="Mantenimiento" @selected("Mantenimiento" == $factura->area) >Mantenimiento</option>
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
                    
                      <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo1) }}')">
                        <i class="fas fa-file"></i> Anexo 1 - {{ $factura->anexo1 }}</button>
                    </li>
                    @endif
                    @if($factura->anexo2)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo2) }}')">
                        <i class="fas fa-file"></i>
                        Anexo 2 - {{ $factura->anexo2 }}</button>
                    </li>
                    @endif
                    @if($factura->anexo3)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo3) }}')">
                        <i class="fas fa-file"></i>
                        Anexo 3 - {{ $factura->anexo3 }}</button>
                    </li>
                    @endif
                    @if($factura->anexo4)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo4) }}')">
                        <i class="fas fa-file"></i>
                        Anexo 4 - {{ $factura->anexo4 }}</button>
                    </li>
                    @endif
                    @if($factura->anexo5)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo5) }}')">
                        <i class="fas fa-file"></i>
                        Anexo 5 - {{ $factura->anexo5 }}</button>
                    </li>
                    @endif
                    @if($factura->anexo6)
                    <li>
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
          <label for="area">Área de costo </label>
          <select class="form-control area_costo" id="area_costo" name="area_costo" required>
            <option value="">Selecciona</option>
            <option value="AREA DE SERVICIOS" @selected( "AREA DE SERVICIOS"==$factura -> area_costo)>AREA DE SERVICIOS</option>
            <option value="CONTRIBUCIONES SERVICIOS PUBLICOS" @selected( "CONTRIBUCIONES SERVICIOS PUBLICOS"==$factura -> area_costo)>CONTRIBUCIONES SERVICIOS PUBLICOS</option>
            <option value="COORDINACION DE INVENTARIO" @selected( "COORDINACION DE INVENTARIO"==$factura -> area_costo)>COORDINACION DE INVENTARIO</option>
            <option value="DIRCOMERCIAL" @selected( "DIRCOMERCIAL"==$factura -> area_costo)>DIRCOMERCIAL</option>
            <option value="DIRCOMPRAS NACIONALES" @selected( "DIRCOMPRAS NACIONALES"==$factura -> area_costo)>DIRCOMPRAS NACIONALES</option>
            <option value="DIRFINANCIERO" @selected( "DIRCOMPRAS NACIONALES"==$factura -> area_costo)>DIRFINANCIERO</option>
            <option value="DIRGESTION HUMANA" @selected( "DIRGESTION HUMANA"==$factura -> area_costo)>DIRGESTION HUMANA</option>
            <option value="DIRLOGISTICA SERVICIOS" @selected( "DIRGESTION HUMANA"==$factura -> area_costo)>DIRLOGISTICA  SERVICIOS</option>
            <option value="DIRMERCADEO" @selected( "DIRMERCADEO"==$factura -> area_costo)>DIRMERCADEO</option>
            <option value="GERENCIA GENERAL" @selected( "GERENCIA GENERAL"==$factura -> area_costo)>GERENCIA GENERAL</option>
            <option value="IMPORTACIONES" @selected( "IMPORTACIONES"==$factura -> area_costo)>IMPORTACIONES</option>
            <option value="INFORMATICA Y MANTENIMIENTO" @selected( "INFORMATICA Y MANTENIMIENTO"==$factura -> area_costo)>INFORMATICA Y MANTENIMIENTO</option>
           
          </select>
      </div>
      <div class="form-group col-md-6" id="centro_costo_div">
    <label for="centro_costo">Centro de costo</label>
    <select class="form-control centro_costo" id="centro_costo" name="centro_costo" >>
        <!-- Las opciones se cargarán dinámicamente aquí --> 
        <option value="{{$factura->centro_costo}}">{{$factura->centro_costo}}</option>
    </select>
   </div>
            <div class="form-group2 col-md-6">
          <div>
          <label for="costo1">Costo 1 </label>
          <input type="text" class="form-control" id="costo1" name="costo1" value="{{$factura->costo1}}">
          </div>

          <div>
          <label for="costo2">Costo 2 </label>
          <input type="text" class="form-control" id="costo2" name="costo2" value="{{$factura->costo2}}">
          </div>
          
          <div>
          <label for="costo3">Costo 3 </label>
          <input type="text" class="form-control" id="costo3" name="costo3" value="{{$factura->costo3}}">
          </div>

          <div>
          <label for="costo4">Costo 4 </label>
          <input type="text" class="form-control" id="costo4" name="costo4" value="{{$factura->costo4}}">
          </div>
      </div>
            <div class="form-group col-md-6">
              <label for="note">Nota</label>
              <textarea class="form-control" id="note" name="note">{{$factura->note}}</textarea>
            </div>
            <div class="modal-footer">
            <button type="submit" class="btn btn-danger" formaction="{{route('cargados.rechazar', ['id' => $factura->id])}}">Rechazar</button>
             
              <button type="submit" class="btn btn-primary">Aprobar</button>
            </div>
          </form>
        </div>
      </div>
      @endforeach
  <script>
    function buscarCUFE() {
      window.open("https://catalogo-vpfe.dian.gov.co/User/SearchDocument", "_blank");
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