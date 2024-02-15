@extends('layouts.header')

@section('content')

<div class="importar">
    <div class="cardImport">
        <h2>Importar Facturas</h2>
    </div>

    <form id="importForm" action="{{ route('invoices.import.excel') }}" method="post" enctype="multipart/form-data">
        @csrf
       
        <div class="form-import">
   
    <label for="file" class="custom-file-upload">Seleccionar Archivo</label>
    <input type="file" name="file" id="file" accept=".xls, .xlsx" required>
</div>

        <button type="submit" class="btn btn-primary">Importar</button>
    </form>
    @if(Session::has('message'))
    <script>
        Swal.fire({
            title: '¡Importación Exitosa!',
            text: '{{ Session::get('message') }}',
            icon: 'success',
            timer: 5000, // Muestra la alerta durante 5 segundos (ajusta según tus necesidades)
            showConfirmButton: false,
            onClose: function () {
                document.getElementById('loaderOverlay').style.display = 'none';
            }
        });
    </script>
    @endif

</div>


<style>
.importar {
    position: relative;
    display: grid;
    width: 30%;
    align-content: center;
    min-height: 350px;
    background: var(--white);
    padding: 20px;
    margin-left: auto;
    margin-right: auto;
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.08);
    border-radius: 20px;
    text-align: center;
}

.cardImport {
    background-color: rgb(33, 33, 42);
    color: #fff;
    padding: 10px;
    border-radius: 5px ;
    align-content: center;
    
}

.cardImport h2 {
    margin: 0;
    align: center;
    color:white;
}

.form-import {
    margin-bottom: 20px;
}

.form-import label {
    display: block;
    margin: 10px;
    align: center;
}

.form-import input[type="file"] {
    display: none; /* Ocultar el input de tipo file */
}

.custom-file-upload {
    cursor: pointer;
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.custom-file-upload:hover {
    background-color: #0056b3;
}



.btn-primary {
    background-color: rgb(33, 33, 42);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-primary:hover {
    background-color: #0056b3;
}

</style>

@endsection