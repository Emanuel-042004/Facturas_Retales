@extends('layouts.header')

@section('content')


<div class="tablas">
  <div class="cardHeader">
    <h2>Facturas Causadas</h2>
  </div>
  <table>
    <thead>
      
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
      @foreach ($causados as $factura)
      @if (!$area || $factura->area === $area)
      <td><span class="status delivered">{{ $factura->status}}</span></td>
      <td>{{ $factura->area }}</td>
      <td>{{ $factura->name }}</td>
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
  <!-- Estilos Bootstrap para la paginación -->
  <div>
    <ul class="pagination">
      <li class="{{ $causados->onFirstPage() ? 'disabled' : '' }}">
        <a href="{{ $causados->previousPageUrl() }}" aria-label="Anterior">
          <span aria-hidden="true">« Anterior</span>
        </a>
      </li>

      <li class="{{ $causados->hasMorePages() ? '' : 'disabled' }}">
        <a href="{{ $causados->nextPageUrl() }}" class="page-link" aria-label="Siguiente">
          <span aria-hidden="true">Siguiente »</span>
        </a>
      </li>
    </ul>
  </div>
</div>

<!-- ================ APROBADAS ================= -->
<div class="popup-background" id="popupBackground"></div>
      @foreach ($causados as $factura)
      <div class="popup" id="facturaCausadaPopup{{$factura->id}}">
        <div class="popup-content">
          <div class="header">
            <h2 class="modal-title">Datos de Factura</h2>
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
            <h1>Factura</h1>
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

            <hr>
            <h1>Causaciones</h1>
            <div class="form-row">
              <div class="form-group col-md-6">
                
                <div class="attachment-box">
                  <ul class="no-bullet">
                    @if($factura->causacion1)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('causaciones/' . $factura->causacion1) }}')">
                        <i class="fas fa-file"></i> causacion 1 - {{ $factura->causacion1 }}</button>
                    </li>
                    @endif
                    @if($factura->causacion2)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('causaciones/' . $factura->causacion2) }}')">
                        <i class="fas fa-file"></i>
                        causacion 2 - {{ $factura->causacion2 }}</button>
                    </li>
                    @endif
                    @if($factura->causacion3)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('causaciones/' . $factura->causacion3) }}')">
                        <i class="fas fa-file"></i>
                        causacion 3 - {{ $factura->causacion3 }}</button>
                    </li>
                    @endif
                    @if($factura->causacion4)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('causaciones/' . $factura->causacion4) }}')">
                        <i class="fas fa-file"></i>
                        causacion 4 - {{ $factura->causacion4 }}</button>
                    </li>
                    @endif
                    @if($factura->causacion5)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('causaciones/' . $factura->causacion5) }}')">
                        <i class="fas fa-file"></i>
                        causacion 5 - {{ $factura->causacion5 }}</button>
                    </li>
                    @endif
                    @if($factura->causacion6)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('causaciones/' . $factura->causacion6) }}')">
                        <i class="fas fa-file"></i>
                        causacion 6 - {{ $factura->causacion6 }}</button>
                    </li>
                    @endif

                  </ul>
                </div>
              </div>
            </div>

            <h1>Comprobantes</h1>
          <div class="form-row">
              <div class="form-group col-md-6">
                <label for="comprobante1">Comprobante 1</label>
                <input type="file" class="form-control-file" id="comprobante{{$factura->id}}_1" name="comprobantes[]" placeholder="cargue aquí">
                <!-- Lista de archivos seleccionados -->
              </div>
          </div>
          <div id="comprobantesContainer{{$factura->id}}"></div>
          <button type="button" class="btn btn-secondary" onclick="agregarComprobante({{$factura->id}})">Agregar Causación</button>

            <div class="form-group col-md-6">
              <label for="note">Nota</label>
              <textarea class="form-control" id="note" name="note">{{$factura->note}}</textarea>
            </div>
            <div class="modal-footer">
              <button type="button" id="cargarBtn{{$factura->id}}" class="btn btn-primary"
          onclick="confirmarCarga('causarFacturaForm{{$factura->id}}', '{{$factura->id}}')">Pago</button>
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
     function agregarComprobante(facturaId) {
    var contadorComprobantes = document.querySelectorAll('#facturaCausadaPopup' + facturaId + ' input[type="file"]').length + 1;
    if (contadorComprobantes <= 3) { // Solo agregar hasta 6 comprobantes
        var nuevaComprobante = '<div class="form-group col-md-6">' +
            '<label for="comprobante' + facturaId + '_' + contadorComprobantes + '">Comprobante ' + contadorComprobantes + '</label>' +
            '<input type="file" class="form-control-file" id="comprobante' + facturaId + '_' + contadorComprobantes + '" name="comprobantes[]" placeholder="cargue aquí">' +
            '</div>';
        document.getElementById('comprobantesContainer' + facturaId).innerHTML += nuevaComprobante;
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No se pueden agregar más de 6 comprobantes.',
            customClass: {
                container: 'swal-overlay' // Agrega una clase personalizada para que SweetAlert use el estilo personalizado
            }
        });
    }
}

function confirmarCarga(formId, facturaId) {
    // Verificar si al menos un archivo ha sido seleccionado
    var files = document.querySelectorAll(' input[type="file"]');
    var archivosAdjuntos = false;

    files.forEach(function (fileInput) {
        if (fileInput.files.length > 0) {
            archivosAdjuntos = true;
        }
    });

    if (!archivosAdjuntos) {
        // Mostrar una alerta de SweetAlert si no se han adjuntado archivos
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Debes adjuntar al menos una causación.',
            customClass: {
                container: 'swal-overlay',
                popup: 'swal-popup',
                header: 'swal-header',
                title: 'swal-title',
                closeButton: 'swal-close-button',
                icon: 'swal-icon',
                image: 'swal-image',
                content: 'swal-content',
                input: 'swal-input',
                actions: 'swal-actions',
                confirmButton: 'swal-confirm-button',
                cancelButton: 'swal-cancel-button',
                footer: 'swal-footer'
            }
        });
        return false; // Evita enviar el formulario si no se han adjuntado archivos
    }

    // Mostrar una confirmación de SweetAlert en lugar de la confirmación del navegador
    Swal.fire({
        title: '¿Estás seguro de que deseas causar la factura?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cargar factura',
        cancelButtonText: 'Cancelar',
        customClass: {
            container: 'swal-overlay',
            popup: 'swal-popup',
            header: 'swal-header',
            title: 'swal-title',
            closeButton: 'swal-close-button',
            icon: 'swal-icon',
            image: 'swal-image',
            content: 'swal-content',
            input: 'swal-input',
            actions: 'swal-actions',
            confirmButton: 'swal-confirm-button',
            cancelButton: 'swal-cancel-button',
            footer: 'swal-footer'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Si el usuario confirma, enviar el formulario y mostrar la animación de carga
            document.getElementById(formId).submit();
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