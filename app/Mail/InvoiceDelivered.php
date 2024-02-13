<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Factura;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public $factura;
    public $user; 
    public $salutation;

    public function __construct($factura, $user, $salutation)
{
    $this->factura = $factura;
    $this->user = $user;
    $this->salutation = $salutation;
}


    public function build()
    {
        return $this->markdown('emails.invoice_delivered')
                    ->subject('Nueva factura Entregada');
    }
}
