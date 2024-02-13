<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Factura Pendiente</title>
</head>
<body style="font-family: 'Arial', sans-serif;">

    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">

        <h2 style="color: #333;">Nueva Factura Pendiente</h2>

        <p>Se ha agregado una nueva factura pendiente:</p>

        <ul>
            <li><strong>Nombre:</strong> {{ $factura->name }}</li>
            <li><strong>Tipo:</strong> {{ $factura->type }}</li>
            <li><strong>Folio:</strong> {{ $factura->folio }}</li>
            <li><strong>Emisor:</strong> {{ $factura->issuer_name }}</li>
            <!-- Agrega más detalles según sea necesario -->
        </ul>

        

    </div>

</body>
</html>
