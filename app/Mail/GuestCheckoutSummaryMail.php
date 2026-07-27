<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuestCheckoutSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function __construct(
        public array $items,
        public float $total
    ) {
    }

    public function build(): self
    {
        return $this
            ->subject('Guest Checkout Order Summary')
            ->view('emails.guest-checkout-summary');
    }
}
