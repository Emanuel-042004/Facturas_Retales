<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura Entregada</title>
</head>
<body>
    <h1 style="color: #3490dc;">Factura Entregada</h1>

    <p>{{ $salutation }}</p>

    <p>Factura con los siguientes detalles:</p>

    <ul>
        <li><strong>Nombre:</strong> {{ $factura->name }}</li>
        <li><strong>Folio:</strong> {{ $factura->folio }}</li>
        <li><strong>Nombre del Emisor:</strong> {{ $factura->issuer_name }}</li>
        <li><strong>NIT del Emisor:</strong> {{ $factura->issuer_nit }}</li>
        <li><strong>Área:</strong> {{ $factura->area }}</li>
        <li><strong>Fecha de Entrega:</strong> {{ $factura->delivery_date }}</li>
        <li><strong>Entregado por:</strong> {{ $factura->delivered_by }}</li>
    </ul>

    <p>Gracias por tu colaboración.</p>

    <p>Saludos</p>
</body>
</html>
