@extends('layouts.header')

@section('content')

<div class="tablas">
    <h1>Reembolsos</h1><br>
    <form action="{{ route('reembolsos.index') }}" method="GET">
    

      <div class="search">
    <label>  
      <input type="text" name="q" placeholder="Buscar" id="searchInput">
      <ion-icon name="search-outline"></ion-icon>
      <button type="submit">
       
       </button>

    </label>
  </div>
    </form>

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
                            <th>Prefijo</th>
                            <th>Nombre de Emisor</th>
                            <th>NIT de Emisor</th>
                            <th>Fecha de Emision</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reembolso->facturas as $factura)
                        <tr>
                            <td>
                            @if (!empty($factura->status))
                                    @if ($factura->status == 'Pendiente')
                                        <span class="status pending">{{ $factura->status }}</span>
                                    @elseif ($factura->status == 'Cargada')
                                        <span class="status loaded">{{ $factura->status }}</span>
                                    @elseif ($factura->status == 'Causada')
                                        <span class="status delivered">{{ $factura->status }}</span>
                                        @elseif ($factura->status == 'Pagada')
                                        <span class="status fefused">{{ $factura->status }}</span>
                                        @elseif ($factura->status == 'Finalizada')
                                        <span class="status approved">{{ $factura->status }}</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @if (!empty($factura->subtype))
                                    @if ($factura->subtype == 'Adjuntada')
                                        <span class="status inProgress">{{ $factura->subtype }}</span>
                                    @elseif ($factura->subtype == 'Rechazada' || $factura->subtype == 'FIN/Rechazada')
                                        <span class="status refused">{{ $factura->subtype }}</span>
                                    @elseif ($factura->subtype == 'Aprobada')
                                        <span class="status approved">{{ $factura->subtype }}</span>
                                        @elseif ($factura->subtype == 'Pag/No Aprobado')
                                        <span class="status approved">{{ $factura->subtype }}</span>
                                    @endif
                                @endif
                            </td>
                            <td>{{ $factura->type }} {{ $reembolso->consecutivo }}</td>
                            <td>{{ $factura->area }}</td>
                            <td>{{ $factura->folio }}</td>
                            <td>{{ $factura->prefix }}</td>
                            <td>{{ $factura->issuer_name }}</td>
                            <td>{{ $factura->issuer_nit }}</td>
                            <td>{{ $factura->issue_date }}</td> 
                            <td>
                                @if ($factura->subtype == 'Adjuntada' && $factura->status == 'Cargada')
                                    <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaCargadaPopup{{$factura->id}}')"></ion-icon>
                                    @elseif(($factura->subtype == 'Adjuntada' || $factura->subtype == 'Pag/No Aprobado') && $factura->status == 'Causada')

                                <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaCausadaPopup{{$factura->id}}')"></ion-icon>
                                @elseif($factura->subtype == 'Adjuntada' && $factura->status == 'Pagada')
                                    <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaPagadaPopup{{$factura->id}}')"></ion-icon>
                                @elseif ($factura->subtype == 'Adjuntada')
                                    <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaAdjuntadaPopup{{$factura->id}}')"></ion-icon>
                                @elseif($factura->subtype == 'Aprobada')
                                    <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaAprobadaPopup{{$factura->id}}')"></ion-icon>
                                @else
                                    <ion-icon name="ellipsis-vertical-outline" onclick="openPopup('facturaPopup{{$factura->id}}')"></ion-icon>
                                @endif
                            </td>
 
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


  <!-- ================ ADJUNTADAS ================= -->
  <div class="popup-background" id="popupBackground"></div>
      @foreach ($reembolso->facturas as $factura)
      <div class="popup" id="facturaCargadaPopup{{$factura->id}}">
        <div class="popup-content">
          <div class="header">
            <h2 class="modal-title">Datos de Factura</h2>
            <span class="close-icon" onclick="closePopup('facturaCargadaPopup{{$factura->id}}')">&times;</span>
          </div>
          <form id="causarFacturaForm{{$factura->id}}" action="{{ route('causar_factura', ['id' => $factura->id]) }}"
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
            <hr>
            <h1>Causaciones</h1>
          <div class="form-row">
              <div class="form-group col-md-6">
                <label for="causacion1">Causación 1</label>
                <input type="file" class="form-control-file" id="causacion{{$factura->id}}_1" name="causaciones[]" placeholder="cargue aquí">
                <!-- Lista de archivos seleccionados -->
              </div>
          </div>
          <div id="causacionesContainer{{$factura->id}}"></div>
          <button type="button" class="btn btn-secondary" onclick="agregarCausacion({{$factura->id}})">Agregar Causación</button>

            <div class="form-group col-md-6">
              <label for="note">Nota</label>
              <textarea class="form-control" id="note" name="note">{{$factura->note}}</textarea>
            </div>
            <div class="modal-footer">
              
              <button type="submit" class="btn btn-danger" formaction="{{route('cargados.rechazar', ['id' => $factura->id])}}">Rechazar</button>
             
              <button type="button" id="cargarBtn{{$factura->id}}" class="btn btn-primary"
          onclick="confirmarCarga('causarFacturaForm{{$factura->id}}', '{{$factura->id}}')">Causar</button>
            </div>
          </form>
        </div>
      </div>
      @endforeach

      <script>
     function agregarCausacion(facturaId) {
    var contadorCausaciones = document.querySelectorAll('#facturaCargadaPopup' + facturaId + ' input[type="file"]').length + 1;
    if (contadorCausaciones <= 6) { // Solo agregar hasta 6 causaciones
        var nuevaCausacion = '<div class="form-group col-md-6">' +
            '<label for="causacion' + facturaId + '_' + contadorCausaciones + '">Causación ' + contadorCausaciones + '</label>' +
            '<input type="file" class="form-control-file" id="causacion' + facturaId + '_' + contadorCausaciones + '" name="causaciones[]" placeholder="cargue aquí">' +
            '</div>';
        document.getElementById('causacionesContainer' + facturaId).innerHTML += nuevaCausacion;
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'No se pueden agregar más de 6 causaciones.',
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
        confirmButtonText: 'Sí',
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

   
<!-- ================ APROBADAS ================= -->
<div class="popup-background" id="popupBackground"></div>
      @foreach ($reembolso->facturas as $factura)
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
              <button type="submit" id="cargar2Btn{{$factura->id}}" class="btn btn-primary">Pago</button>
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
            text: 'No se pueden agregar más de 3 comprobantes.',
            customClass: {
                container: 'swal-overlay' // Agrega una clase personalizada para que SweetAlert use el estilo personalizado
            }
        });
    }
}

</script>


<!-- ================ PAGADAS ================= -->
<div class="popup-background" id="popupBackground"></div>
      @foreach ($reembolso->facturas as $factura)
      <div class="popup" id="facturaPagadaPopup{{$factura->id}}">
        <div class="popup-content">
          <div class="header">
            <h2 class="modal-title">Datos de Factura</h2>
            <span class="close-icon" onclick="closePopup('facturaPagadaPopup{{$factura->id}}')">&times;</span>
          </div>
          <form id="egresoFacturaForm{{$factura->id}}" action="{{ route('finalizar', ['id' => $factura->id]) }}"
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
            <h2>Causaciones</h2>
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

            <h2>Comprobante de Pago</h2>
            <div class="form-row">
              <div class="form-group col-md-6">
                
                <div class="attachment-box">
                  <ul class="no-bullet">
                    @if($factura->comprobante1)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('comprobantes/' . $factura->comprobante1) }}')">
                        <i class="fas fa-file"></i> comprobante 1 - {{ $factura->comprobante1 }}</button>
                    </li>
                    @endif
                    @if($factura->comprobante2)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('comprobantes/' . $factura->comprobante2) }}')">
                        <i class="fas fa-file"></i>
                        comprobante 2 - {{ $factura->comprobante2 }}</button>
                    </li>
                    @endif
                    @if($factura->comprobante3)
                    <li>
                      <button class="btn " onclick="openDocument('{{ asset('comprobantes/' . $factura->comprobante3) }}')">
                        <i class="fas fa-file"></i>
                        comprobante 3 - {{ $factura->comprobante3 }}</button>
                    </li>
                    @endif
                  </ul>
                </div>
              </div>
            </div>
            <h2>Comprobante de Egreso</h2>
          <div class="form-row">
              <div class="form-group col-md-6">
                <label for="egreso">Comprobante 1</label>
                <input type="file" class="form-control-file" id="egreso" name="egreso" placeholder="cargue aquí">
                <!-- Lista de archivos seleccionados -->
              </div>
          </div>
          

            <div class="form-group col-md-6">
              <label for="note">Nota</label>
              <textarea class="form-control" id="note" name="note">{{$factura->note}}</textarea>
            </div>
            <div class="modal-footer">
        <button type="submit" class="btn btn-danger" formaction="{{route('rechazar_p', ['id' => $factura->id])}}">Rechazar</button>
        <button type="button" id="cargarBtn2{{$factura->id}}" class="btn btn-primary" onclick="confirmarCarga2('{{$factura->id}}')">Finalizar</button>
      </div>
    </form>
  </div>
</div>
@endforeach

<script>
  function confirmarCarga2(facturaId) {
    var egresoInput = document.getElementById('egreso');
    
   
    // Mostrar una confirmación de SweetAlert en lugar de la confirmación del navegador
    Swal.fire({
      title: '¿Estás seguro de que deseas causar la factura?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, cargar factura',
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
        document.getElementById('egresoFacturaForm' + facturaId).submit();
        document.getElementById('cargarBtn2' + facturaId).style.display = "none"; // Ocultar el botón de "cargar"
        document.getElementById('loading' + facturaId).style.display = "block"; // Mostrar la animación de carga
      }
    });
  }
</script>


<!-- ================ PENDIENTES - CARGAR FACTURA ================= -->
<div class="popup-background" id="popupBackground"></div>
@foreach ($reembolso->facturas as $factura)
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
      @if($factura->subtype == 'Rechazada' || $factura->subtype == 'FIN/Rechazada')
      <div class="form-group col-md-6">
          <label for="anexos">Archivos Adjuntos</label>
          <div class="attachment-box">
            <ul class="no-bullet">
            @if($factura->anexo1)
              <li>
                <button class="btn " onclick="openDocument('{{ asset('anexos/' . $factura->anexo1) }}')">
                  <i class="fas fa-file"></i>
                  Anexo 2 - {{ $factura->anexo1 }}</button>
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
     
      <div class="form-group col-md-6">
        <label for="note">Nota</label>
        <textarea class="form-control" id="note" name="note" >{{$factura->note}}</textarea>
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
    // Aquí puedes agregar el código para manejar la acción de búsqueda del CUFE
    // Por ejemplo, podrías abrir una nueva ventana o redirigir a una página de búsqueda
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
      // Mostrar alerta de SweetAlert con el z-index personalizado
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
      title: '¿Estás seguro de que deseas cargar el anexo?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí',
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
