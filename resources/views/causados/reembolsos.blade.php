@extends('layouts.header')

@section('content')


<div class="tablas">
  <div class="cardHeader">
    <h2>Reembolsos Causados</h2>
  </div>
  <div class="search">
        <form action="{{ route('reembolsos_causados.index') }}" method="GET">
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
      <td>Reembolso</td>
      <td>Estado</td>
      <td>Area</td>
      <td>Prefijo</td>
      <td>Folio</td>
      <td>Nombre de Emisor</td>
      <td>NIT de Emisor</td>
      <td>Acciones</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($facturas as $factura)
      @if (!$area || $factura->area === $area)
      <td>{{ $factura->reembolso->consecutivo }}</td>
      <td><span class="status delivered">{{ $factura->status}}</span></td>
      <td>{{ $factura->area }}</td>
      <td>{{ $factura->prefix }}</td>
      <td>{{ $factura->folio}}</td>
      <td>{{ $factura->issuer_name}}</td>
      <td>{{ $factura->issuer_nit }}</td>
      <td>
         <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaCausadaPopup{{$factura->id}}')"></ion-icon>             
      </td>
      
      </tr>
      @endif
      @endforeach
    </tbody>
  </table>
  
</div>

<!-- ================ APROBADAS ================= -->
<div class="popup-background" id="popupBackground"></div>
      @foreach ($facturas as $factura)
      <div class="popup" id="facturaCausadaPopup{{$factura->id}}">
        <div class="popup-content">
          <div class="header">
            <h2 class="modal-title">Datos de Factura - Reembolso {{ $factura->reembolso->consecutivo }}</h2>
            <span class="close-icon" onclick="closePopup('facturaCausadaPopup{{$factura->id}}')">&times;</span>
          </div>
          <form id="causarFacturaForm{{$factura->id}}" action="{{ route('comprobar_factura', ['id' => $factura->id]) }}"
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
                <option value="Legalizacion" @selected("Legalizacion" == $factura->type) >Legalizacion</option>

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
              <hr>
            <h2>Factura</h2>
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
            <option value="ARRENDAMIENTOS" @selected( "ARRENDAMIENTOS"==$factura -> area_costo)>ARRENDAMIENTOS</option>
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
            <option value="IMPUESTOS" @selected( "IMPUESTOS"==$factura -> area_costo)>IMPUESTOS</option>
            <option value="INFORMATICA Y MANTENIMIENTO" @selected( "INFORMATICA Y MANTENIMIENTO"==$factura -> area_costo)>INFORMATICA Y MANTENIMIENTO</option>
            <option value="NOMINA DE GASTOS NO ASIGNADOS" @selected( "NOMINA DE GASTOS NO ASIGNADOS"==$factura -> area_costo)>NOMINA DE GASTOS NO ASIGNADOS</option>
            <option value="PUNTOS DE VENTA" @selected( "PUNTOS DE VENTA"==$factura -> area_costo)>PUNTOS DE VENTA</option>
            <option value="TRAMITES LEGALES" @selected( "TRAMITES LEGALES"==$factura -> area_costo)>TRAMITES LEGALES</option>
          </select>
      </div>
      <div class="form-group col-md-6" id="centro_costo_div">
    <label for="centro_costo">Centro de costo</label>
    <select class="form-control centro_costo" id="centro_costo" name="centro_costo" >>
        <!-- Las opciones se cargarán dinámicamente aquí --> 
        <option value="{{$factura->centro_costo}}">{{$factura->centro_costo}}</option>
    </select>
   </div>

        <hr>
        <h2>Comprobante de Egreso</h2>
        <div class="attachment-box">
          <ul class="no-bullet">
            @if($factura->egreso)
            <li>
              <button class="btn " onclick="openDocument('{{ asset('egresos/' . $factura->egreso) }}')">
              <i class="fas fa-file"></i>
              Com/Egreso - {{ $factura->egreso }}</button>
            </li>
            @endif
         </ul>
        </div>


    <div class="form-row">
        <div class="form-group col-md-6">
          <label for="egreso">Comprobante</label>
          <input type="file" class="form-control-file" id="egreso" name="egreso" placeholder="cargue aquí">
          <!-- Lista de archivos seleccionados -->
        </div>
    </div>

            <div class="form-group col-md-6">
              <label for="note">Nota</label>
              <textarea class="form-control" id="note" name="note">{{$factura->note}}</textarea>
            </div>
            <div class="modal-footer">
            <button type="button" id="cargarBtn{{$factura->id}}" class="btn btn-primary" onclick="confirmarCarga('{{$factura->id}}')">PAGO</button>
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

<script>
    function confirmarCarga(facturaId) {
    var egresoInput = document.getElementById('egreso');
    
    // Verifica si se ha adjuntado un archivo para el campo egreso
    if (!egresoInput.files || egresoInput.files.length === 0) {
      // Muestra una alerta si no se ha adjuntado un archivo para el campo egreso
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Debes adjuntar un archivo para el campo egreso.',
        showCloseButton: false, // Evita que se muestre el botón de cierre
        customClass: {
          container: 'swal-overlay',
          popup: 'swal-popup',
          header: 'swal-header',
          title: 'swal-title',
          icon: 'swal-icon',
          content: 'swal-content',
          confirmButton: 'swal-confirm-button'
        }
      });
      return false; // Evita enviar el formulario si no se ha adjuntado un archivo para el campo egreso
    }

    // Mostrar una confirmación de SweetAlert en lugar de la confirmación del navegador
    Swal.fire({
      title: '¿Deseas pasar la factura a la seccion de Pagados?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí',
      cancelButtonText: 'Cancelar',
      showCloseButton: false, // Evita que se muestre el botón de cierre
      customClass: {
        container: 'swal-overlay',
        popup: 'swal-popup',
        header: 'swal-header',
        title: 'swal-title',
        icon: 'swal-icon',
        content: 'swal-content',
        confirmButton: 'swal-confirm-button',
        cancelButton: 'swal-cancel-button'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        // Si el usuario confirma, enviar el formulario y mostrar la animación de carga
        document.getElementById('causarFacturaForm' + facturaId).submit();
        document.getElementById('cargarBtn' + facturaId).style.display = "none"; // Ocultar el botón de "cargar"
        document.getElementById('loading' + facturaId).style.display = "block"; // Mostrar la animación de carga
      }
    });
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