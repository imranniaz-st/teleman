<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $invoice = $this->details->invoice;

        return $this->view('frontend.success.invoice')
                    ->with('details')
                    ->attach(asset('invoice_pdf/'.$invoice.'.pdf'), [
                        'mime' => 'application/pdf',
                    ])->subject('Invoice: Registration of '.$this->details->domain);
    }
}
