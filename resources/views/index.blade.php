@extends('layouts.header')

@section('content')

<div class="col-8 mt-4">

    <div class="container mt-6">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Importar Facturas desde Excel</h3>
                    </div>
                    <div class="card-body">
                        <form id="importForm" action="{{ route('invoices.import.excel') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="file" class="form-label">Seleccionar Archivo Excel</label>
                                <input type="file" class="form-control" name="file" id="file" accept=".xls, .xlsx" required>
                            </div>

                            <button type="submit" class="btn btn-primary" onclick="showLoader()">Importar</button>
                        </form>
                        
                        <div class="loader-overlay" id="loaderOverlay">
                            <div class="loader"></div>
                        </div>
                        @if(Session::has('message'))
                            <script>
                                Swal.fire({
                                    title: '¡Importación Exitosa!',
                                    text: '{{ Session::get('message') }}',
                                    icon: 'success',
                                    timer: 5000, // Muestra la alerta durante 5 segundos (ajusta según tus necesidades)
                                    showConfirmButton: false,
                                    onClose: function() {
                                        document.getElementById('loaderOverlay').style.display = 'none';
                                    }
                                });
                            </script>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showLoader() {
            document.getElementById('loaderOverlay').style.display = 'block';
            setTimeout(hideLoader, 5000); // Oculta el loader después de 5 segundos (ajusta según tus necesidades)
        }

        function hideLoader() {
            document.getElementById('loaderOverlay').style.display = 'none';
            document.getElementById('importSuccess').style.display = 'none'; // Oculta el mensaje de importación exitosa
        }
    </script>

</div>

@endsection
