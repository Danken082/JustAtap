<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CorporateCardOrderRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $companyName,
        public string $companyEmail,
        public string $orderLabel,
        public int $quantity,
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Corporate card order request from '.$this->companyName)
            ->view('emails.corporate-card-order-request');
    }
}
