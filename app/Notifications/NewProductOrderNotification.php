<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewProductOrderNotification extends Notification
{
    use Queueable;

    /**
     * @param array<int, array<string, mixed>> $items
     */
    public function __construct(
        public string $customerName,
        public string $customerEmail,
        public array $items,
        public float $total,
    ) {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New product order',
            'message' => $this->customerName.' placed an order worth PHP '.number_format($this->total, 2).'.',
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'item_count' => count($this->items),
            'total' => $this->total,
            'url' => route('admin.dashboard'),
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
