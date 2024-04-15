
//CENTROS DE COSTOS
document.addEventListener("DOMContentLoaded", function() {
    var areaCostoSelects = document.querySelectorAll('.area_costo');
    var subAreaDivs = document.querySelectorAll('.centro_costo_div');
    var subAreaSelects = document.querySelectorAll('.centro_costo');
    
    areaCostoSelects.forEach(function(areaCostoSelect, index) {
        areaCostoSelect.addEventListener('change', function() {
            var selectedArea = this.value;
            var subAreaDiv = subAreaDivs[index];
            var subAreaSelect = subAreaSelects[index];
            
            // Aquí puedes hacer una petición AJAX para obtener las opciones
            // del segundo select basadas en la selección del primero.
            // Por ahora, usaré un ejemplo estático.
            var options = getOptionsForArea(selectedArea);
            
            // Limpiamos el segundo select
            subAreaSelect.innerHTML = '';
              
            // Agregamos las nuevas opciones al segundo select
            options.forEach(function(option) {
                var optionElement = document.createElement('option');
                optionElement.value = option;
                optionElement.textContent = option;
                subAreaSelect.appendChild(optionElement);
            });
            
            // Mostramos u ocultamos el div de subárea según las opciones
            if (options.length > 0) {
                subAreaDiv.style.display = 'block';
            } else {
                subAreaDiv.style.display = 'none';
            }
        });
    });
    
    // Función de ejemplo para obtener opciones del segundo select
    function getOptionsForArea(selectedArea) {
        // Puedes reemplazar esto con una petición AJAX real a tu backend en Laravel
        // para obtener las opciones del segundo select basadas en la selección del primero.
        // Por ahora, solo devolveremos algunas opciones estáticas para el ejemplo.
        if (selectedArea === 'AREA DE SERVICIOS') {
            return ['AREA SERVICIOS ADMINISTRATIVOS ', 
            'AREA SERVICIOS MAQUINARIA ', 
            'AREA SERVICIOS NOMINA', 
            'TRANSPORTE Y ALIMENTACION LOCAL'];

        } else if (selectedArea === 'ARRENDAMIENTOS') {
            return ['ARRENDAMIENTOS'];

        } else if (selectedArea === 'CONTRIBUCIONES SERVICIOS PUBLICOS') {
            return ['CONTRIBUCIONES SERVICIOS PUBLICOS'];

        } else if (selectedArea === 'COORDINACION DE INVENTARIO') {
            return ['ADECUACIONES LOCATIVAS',
            'ADMINISTRATIVOS',
            'NOMINA',
            'COORDINACION INVENTARIOS',
            'PROYECTOS DE MEJORA',
            'TRANSPORTE Y ALIMENTACION LOCAL'];

        } else if (selectedArea === 'DIRCOMERCIAL') {
            return ['ADMINISTRATIVOS',
            'DIRCOMERCIAL',
            'NOMINA',
            'MUESTRAS Y OTROS DE CLIENTES',
            'SERVICIOS RELACIONADOS',
            'TRANSPORTE Y ALIMENTACION LOCAL VIAJES Y CORRERIAS'];

        } else if (selectedArea === 'DIRCOMPRAS NACIONALES') {
            return [ 'ADMINISTRATIVOS',
            'DIRCOMPRAS NOMINA',
            'FERIAS Y EVENTOS',
            'SUCRIPCIONES',
            'ACCESO A NORMAS',
            'TRANSPORTE Y ALIMENTACION LOCAL',
            'VIAJES Y CORRERIAS'];

        } else if (selectedArea === 'DIRFINANCIERO') {
            return ['ADMINISTRATIVOS',
            'ASEGURAMIENTO DE CARTERA',
            'CONTINGENCIAS',
            'SINIESTROS Y SIMILARES',
            'DEPRECIACIONES',
            'DETERIORO DE INVENTARIOS',
            'DIRFINANCIERO NOMINA',
            'EJERCICIOS ANTERIORES',
            'FINANCIEROS',
            'HONORARIOS',
            'MANTENIMIENTO LOCATIVO',
            'SEGUROS',
            'SUSCRIPCIONES ACCESO A NORMAS'];

        } else if (selectedArea === 'DIRGESTION HUMANA') {
            return ['ACTIVIDADES DE BIENESTAR',
            'ADMINISTRATIVOS',
            'CAPACITACIONES',
            'DIRGESTION HUMANA NOMINA',
            'DOTACION',
            'HONORARIOS',
            'SEGURIDAD Y SALUD EN EL TRABAJO',
            'SELECCION, CONTRATACION Y SIMILARES',
            'TRANSPORTE Y ALIMENTACION LOCAL',
            'VIAJES Y CORRERIAS'];

        } else if (selectedArea === 'DIRLOGISTICA SERVICIOS') {
            return ['ADMINISTRATIVOS',
            'BODEGAS',
            'COMBUSTIBLE',
            'DIRLOGISTICA NOMINA',
            'FLETES Y SIMILARES',
            'OTROS INSUMOS',
            'TRANSPORTE Y ALIMENTACION LOCAL',
            'VEHICULOS',
            'VIAJES Y CORRERIAS'];

        } else if (selectedArea === 'DIRMERCADEO') {
            return [ 'ADECUACIONES PUNTO DE VENTA',
            'ADMINISTRATIVOS',
            'DIRMERCADEO NOMINA',
            'FERIAS Y EVENTOS',
            'NOMINA MERCADEO Y SAC',
            'PUBLICIDAD, MUESTRAS Y OTROS',
            'SERVICIO AL CLIENTE',
            'TRANSPORTE Y ALIMENTACION LOCAL',
            'VIAJES Y CORRERIAS'];

        } else if (selectedArea === 'GERENCIA GENERAL') {
            return [  'ADECUACIONES PUNTO DE VENTA',
            'ADMINISTRATIVOS',
            'EVENTO COMERCIAL ANIVERSARIO 2023',
            'GERENCIA GENERAL NOMINA',
            'HONORARIOS',
            'INCENTIVOS COLABORADORES PRODUTIVIDAD',
            'INVERSION EN ACTIVOS',
            'INVERSIONES EN ACTIVOS INTANGIBLES',
            'PROYECTOS DE INFRAESTRUCTURA',
            'SEGUROS Y VIGILANCIA',
            'TRANSPORTE Y ALIMENTACION LOCAL',
            'VIAJES Y CORRERIAS'];

        } else if (selectedArea === 'IMPORTACIONES') {
            return ['ADMINISTRATIVOS', 'NOMINA IMPORTACIONES'];

        } else if (selectedArea === 'IMPUESTOS') {
            return ['IMPUESTOS'];

        } else if (selectedArea === 'INFORMATICA Y MANTENIMIENTO') {
            return [  'ADMINISTRATIVOS',
            'ALQUILER EQUIPOS DE COMPUTO',
            'ASEO, ACUEDUCTO Y ALCANTARILLADO',
            'ASESORIAS',
            'CONTRIBUCION SERVICIOS PUBLICOS',
            'DIR INFORMATICA NOMINA',
            'ENERGIA ELECTRICA',
            'INTERNET',
            'MTTO ELECTRICO Y DE SISTEMAS',
            'MTTO LOCATIVO',
            'PROGRAMAS Y LICENCIAS',
            'TELECOMUNICACIONES',
            'TRANSPORTE Y ALIMENTACION LOCAL',
            'VIGILANCIA'];

        } else if (selectedArea === 'NOMINA DE GASTOS NO ASIGNADOS') {
            return ['0'];

        } else if (selectedArea === 'PUNTOS DE VENTA') {
            return [  'ADMINISTRATIVOS',
            'GASTOS PREOPERATIVOS',
            'PUNTOS DE VENTA NOMINA',
            'TRANSPORTE Y ALIMENTACION LOCAL'];

        } else if (selectedArea === 'TRAMITES LEGALES') {
            return ['ADMINISTRATIVOS',
            'GASTOS PREOPERATIVOS',
            'PUNTOS DE VENTA NOMINA',
            'TRAMITES LEGALES',
            'TRANSPORTE Y ALIMENTACION LOCAL'];
        
        } else {
            return [];
        }
    }
});



//ABRIR PDF

function openPopup(popupId) {
    document.getElementById(popupId).style.display = "block";
    document.getElementById('popupBackground').style.display = "block"; // Mostrar el fondo gris
  }

  function closePopup(popupId) {
    document.getElementById(popupId).style.display = "none";
    document.getElementById('popupBackground').style.display = "none"; // Ocultar el fondo gris
  }

  //Abrir PopUp 
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
