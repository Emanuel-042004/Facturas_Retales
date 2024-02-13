@component('mail::message')
# Factura Recibida

Hola {{ $factura->delivered_by }},

Queremos informarte que la factura con los siguientes detalles ha sido recibida:

- **Nombre:** {{ $factura->name }}
- **Folio:** {{ $factura->folio }}
- **Nombre del Emisor:** {{ $factura->issuer_name }}
- **NIT del Emisor:** {{ $factura->issuer_nit }}
- **Área:** {{ $factura->area }}
- **Fecha de Entrega:** {{ $factura->delivery_date }}
- **Recibido por:** {{ $factura->received_by }}
- **Fecha de Recepción:** {{ $factura->received_date }}

Gracias por tu colaboración.

Saludos,
{{ config('app.name') }}
@endcomponent
