<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DomainInvoiceMail extends Mailable implements ShouldQueue
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

        return $this->view('frontend.success.domain_invoice')
                    ->with('details')
                    ->attach(asset('domain_invoice_pdf/'.$invoice.'.pdf'), [
                        'mime' => 'application/pdf',
                    ])->subject('Completed: Registration of '.$this->details->domain);
    }
}
