@extends('layouts.header')

@section('content')


<div class="tablas">
  <div class="cardHeader">
    <h2>Facturas DIAN</h2>
    <!-- Botones ocultos al principio -->
    <div id="botonesContainer" style="display: none;">
    <form id="reembolsoForm" action="{{ route('crear_reembolso') }}" method="POST">
    @csrf
    <input type="hidden" name="ids[]" id="idsInput">
    <button type="button" class="btn" onclick="cambiarTipo('Reembolso')">Reembolso</button>
   </form>

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
      <td>Proceso</td>
      <td>Tipo</td>
      <td>Area</td>
      <td>Folio</td>
      <td>Prefijo</td>
      <td>Nombre de Emisor</td>
      <td>NIT de Emisor</td>
      <td>Fecha de Emision</td>
      <td>Entregado por</td>
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
      <td>
        @if (!empty($factura->subtype))
        @if ($factura->subtype == 'Adjuntada')
        <span class="status inProgress">{{ $factura->subtype }}</span>
        @else
        <span class="status refused">{{ $factura->subtype }}</span>
        @endif
        @endif
      </td>
      <td>{{ $factura->type }}</td>
      <td>{{ $factura->area }}</td>
      <td>{{ $factura->folio}}</td>
      <td>{{ $factura->prefix}}</td>
      <td>{{ $factura->issuer_name}}</td>
      <td>{{ $factura->issuer_nit }}</td>
      <td>{{ $factura->issue_date }}</td>
      <td>{{ $factura->delivered_by }}</td>
      <td>
        @if ($factura->subtype == 'Adjuntada')
        <ion-icon name="ellipsis-vertical-outline"
          onclick="openPopup('facturaAdjuntadaPopup{{$factura->id}}')"></ion-icon>
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
    var form = document.getElementById('reembolsoForm');

    // Verificar si todas las facturas seleccionadas tienen el subtype "Adjuntada"
    var todasAdjuntadas = Array.from(checkboxes).every(function (checkbox) {
        var row = checkbox.closest('tr');
        var subtype = row.querySelector('td:nth-child(3)').textContent.trim();
        return subtype === 'Adjuntada';
    });

    // Si todas las facturas están adjuntadas, continuar con la creación del reembolso
    if (todasAdjuntadas) {
        // Eliminar los campos de entrada ocultos existentes
        var hiddenInputs = document.querySelectorAll('input[name="ids[]"]');
        hiddenInputs.forEach(function (input) {
            form.removeChild(input);
        });

        // Crear un nuevo campo de entrada oculto para cada ID de factura seleccionada
        checkboxes.forEach(function (checkbox) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = checkbox.value;
            form.appendChild(input);
        });

        // Realizar la petición AJAX para crear el reembolso
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form)
        })
        .then(response => {
            if (response.ok) {
                // Si la respuesta es exitosa, mostrar mensaje de éxito
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Se ha creado el reembolso correctamente.',
                });
            } else {
                // Si hay un error, mostrar mensaje de error
                Swal.fire({
                    icon: 'error',
                    title: 'Opps',
                    text: 'Ha ocurrido un error al crear el reembolso.',
                });
            }
        });
    } else {
        // Mostrar un mensaje de error indicando que todas las facturas deben ser adjuntadas
        Swal.fire({
            icon: 'error',
            title: 'Opps',
            text: 'Solo se pueden crear reembolsos cuando todas las facturas seleccionadas tienen el subtype "Adjuntada".',
        });
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

        <div class="form-group col-md-6">
          <label for="type">Tipo</label>
          <select class="form-control" id="type" name="type">
            <option value="">Selecciona</option>
            <option value="Factura electrónica">Factura electrónica</option>
            <option value="Nota de crédito electrónica">Nota de crédito electrónica</option>
          </select>
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
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="folio">Folio</label>
          <input type="text" class="form-control" id="folio" name="folio" placeholder="Contrato">
        </div>
      
        
        <div class="form-group col-md-6">
          <label for="prefix">Prefijo</label>
          <input type="text" class="form-control" id="prefix" name="prefix">
        </div>
     
        <div class="form-group col-md-6">
          <label for="issuer_name">Nombre Emisor</label>
          <input type="text" class="form-control" id="issuer_name" name="issuer_name">
        </div>
        <div class="form-group col-md-6">
          <label for="issuer_nit">Nit Emisor</label>
          <input type="text" class="form-control" id="issuer_nit" name="issuer_nit">
        </div>
        
      </div>

      <div class="modal-footer">

        <button type="submit" class="btn btn-primary">Crear</button>
      </div>
    </form>
  </div>
</div>

<!-- ================ ADJUNTADAS ================= -->
<div class="popup-background" id="popupBackground"></div>
@foreach ($pendientes as $factura)
<div class="popup" id="facturaAdjuntadaPopup{{$factura->id}}">
  <div class="popup-content">
    <div class="header">
      <h2 class="modal-title">Datos de Factura</h2>
      <span class="close-icon" onclick="closePopup('facturaAdjuntadaPopup{{$factura->id}}')">&times;</span>
    </div>
    <form id="aprobarFacturaForm{{$factura->id}}" action="{{ route('pendientes.aprobar', ['id' => $factura->id]) }}"
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
    <button type="submit" class="btn btn-danger" formaction="{{route('pendientes.rechazar', ['id' => $factura->id])}}">Rechazar</button>
    <button type="submit" class="btn btn-primary">Aprobar</button>
</div>
    </form>
  </div>
</div>
@endforeach

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
          <option value="Nota de crédito electrónica" @selected( "Nota de crédito electrónica"==$factura ->type)>Nota de crédito electrónica</option>
          <option value="Legalizacion" @selected( "Legalizacion"==$factura -> type) >Legalizacion</option>

        </select>
      </div>
      <div class="form-row">
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
          <select class="form-control" id="area" name="area" required>
            <option value="">Selecciona</option>
            <option value="Compras" @selected( "Compras"==$factura -> area)>Compras</option>
            <option value="Financiera" @selected( "Financiera"==$factura -> area)>Financiera</option>
            <option value="Logistica" @selected( "Logistica"==$factura -> area)>Logística</option>
            <option value="Mantenimiento" @selected( "Mantenimiento"==$factura -> area) >Mantenimiento</option>
            <option value="Tecnologia" @selected( "Tecnologia"==$factura -> area)>Tecnología</option>
          </select>
        </div>
      </div>
      @if($factura->subtype == 'Rechazada' || $factura->subtype == 'FIN/Rechazada')
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
      @endif
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="anexo1">Anexo 1</label>
          <input type="file" class="form-control-file" id="anexo{{$factura->id}}_1" name="anexos[]" placeholder="cargue aquí">
          <!-- Lista de archivos seleccionados -->
        </div>
      </div>
      <div id="anexosContainer{{$factura->id}}"></div>
      <button type="button" class="btn btn-secondary" onclick="agregarAnexo({{$factura->id}})">Agregar Anexo</button>

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
        <!-- Botón de "cargar" -->
        <button type="button" id="cargarBtn{{$factura->id}}" class="btn btn-primary"
          onclick="confirmarCarga('cargarFacturaForm{{$factura->id}}', '{{$factura->id}}')">Cargar</button>
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
  function buscarCUFE() {
    window.open("https://catalogo-vpfe.dian.gov.co/User/SearchDocument", "_blank");
  }
</script>

<script>
  function agregarAnexo(facturaId) {
    var contadorAnexos = document.querySelectorAll('#facturaPopup' + facturaId + ' input[type="file"]').length + 1;
    if (contadorAnexos <= 6) { // Solo agregar hasta 6 anexos
      var nuevoAnexo = '<div class="form-group col-md-6">' +
        '<label for="anexo' + facturaId + '_' + contadorAnexos + '">Anexo ' + contadorAnexos + '</label>' +
        '<input type="file" class="form-control-file" id="anexo' + facturaId + '_' + contadorAnexos + '" name="anexos[]" placeholder="cargue aquí">' +
        '</div>';

      document.getElementById('anexosContainer' + facturaId).innerHTML += nuevoAnexo;
    } else {
      
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'No se pueden agregar más de 6 anexos.',
        customClass: {
          container: 'swal-overlay' // Agrega una clase personalizada para que SweetAlert use el estilo personalizado
        }
      });
    }
  }
</script>

<script>
  function confirmarCarga(formId, facturaId) {
    // Verificar si al menos un archivo ha sido seleccionado
    var files = document.querySelectorAll('input[type="file"]');
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
        text: 'Debes adjuntar al menos un anexo.',
        // Establecer z-index
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
      title: '¿Estás seguro de que deseas cargar la factura?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, cargar factura',
      cancelButtonText: 'Cancelar',
      // Establecer z-index
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