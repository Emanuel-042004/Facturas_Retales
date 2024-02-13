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