<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Factura;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $factura;

    public function __construct($factura)
    {
        $this->factura = $factura;
    }

    public function build()
    {
        return $this->markdown('emails.invoice_received')
            ->subject('Factura Recibida');
    }
}
