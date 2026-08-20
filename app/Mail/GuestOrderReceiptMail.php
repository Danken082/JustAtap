<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GuestOrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function __construct(
        public array $items,
        public float $total,
        public string $customerName,
        public string $customerEmail,
    ) {
    }

    public function build(): self
    {
        return $this
            ->to($this->customerEmail, $this->customerName)
            ->subject('Your order receipt from JustAtap')
            ->view('emails.guest-order-receipt');
    }
}
