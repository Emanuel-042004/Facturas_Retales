<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
   <h1>Exportar datos</h1>
   

   <form action="{{ route('invoices.import.excel') }}" method="post" enctype="multipart/form-data">
    @csrf  
    
  @if(Session::has('message'))
  <p>{{ Session::get('message') }}</p> 
  @endif 

<input type="file" name="file">
<button>Importar </button> 

</form> 
</body>
</html>