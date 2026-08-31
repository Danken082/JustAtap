<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProfileContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public UserProfile $profile,
        public User $user,
        public string $contactName,
        public string $recipientEmail,
        public string $phone,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your contact card from '.$this->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.profile-contact',
            with: [
                'profile' => $this->profile,
                'user' => $this->user,
                'contactName' => $this->contactName,
                'recipientEmail' => $this->recipientEmail,
                'phone' => $this->phone,
            ],
        );
    }

    public function attachments(): array
    {
        $safeName = preg_replace('/[^A-Za-z0-9._-]+/', '-', trim($this->user->name ?: 'contact')) ?: 'contact';
        $vCard = $this->buildVCard();

        return [
            Attachment::fromData(fn () => $vCard, $safeName.'.vcf')
                ->withMime('text/vcard'),
        ];
    }

    protected function buildVCard(): string
    {
        $name = trim($this->contactName ?: $this->user->name);
        $email = trim($this->recipientEmail ?: $this->user->email);
        $phone = trim($this->phone);

        $lines = [
            'BEGIN:VCARD',
            'VERSION:3.0',
            'FN:'.$name,
        ];

        $nameParts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [$name];
        $first = $nameParts[0] ?? '';
        $last = implode(' ', array_slice($nameParts, 1)) ?: '';

        $lines[] = 'N:'.$last.';'.$first.';;;';

        if ($email !== '') {
            $lines[] = 'EMAIL:'.$email;
        }

        if ($phone !== '') {
            $lines[] = 'TEL;TYPE=CELL:'.$phone;
        }

        if ($this->user->profile?->title) {
            $lines[] = 'TITLE:'.$this->user->profile->title;
        }

        if ($this->user->profile?->bio) {
            $lines[] = 'NOTE:'.$this->user->profile->bio;
        }

        $lines[] = 'URL:'.route('profile.public', ['cardId' => $this->user->card_id]);
        $lines[] = 'END:VCARD';

        return implode("\r\n", $lines)."\r\n";
    }
}
