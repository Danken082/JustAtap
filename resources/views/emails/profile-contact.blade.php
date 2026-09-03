<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile contact</title>
</head>
<body style="margin:0;padding:24px;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <div style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:12px;padding:24px;box-shadow:0 8px 22px rgba(15,23,42,.08);">
        <h2 style="margin:0 0 12px;font-size:24px;">Your contact card is here</h2>
        <p style="margin:0 0 16px;line-height:1.6;color:#374151;">
            Hi {{ $contactName }},
        </p>
        <p style="margin:0 0 16px;line-height:1.6;color:#374151;">
            {{ $user->name }} shared a Smart Tap contact card with you. A .vcf file with their details has been attached to this email.
        </p>
        <p style="margin:0 0 16px;line-height:1.6;color:#374151;">
            <strong>Profile:</strong> <a href="{{ route('profile.public', ['cardId' => $user->card_id]) }}">Open profile</a>
        </p>
        <p style="margin:0;color:#6b7280;font-size:12px;">
            This email was sent from Just A Tap.
        </p>
    </div>
</body>
</html>
