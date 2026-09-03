<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Card Order Request</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937; background: #f8fafc; margin: 0; padding: 32px 16px;">
    <div style="max-width: 620px; margin: 0 auto; background: #ffffff; border-radius: 12px; padding: 28px; border: 1px solid #e5e7eb;">
        <h2 style="margin: 0 0 16px; color: #0f172a;">Corporate card order request</h2>
        <p style="margin: 0 0 12px;">A corporate admin has requested a new batch of cards.</p>

        <p style="margin: 0 0 8px;"><strong>Company:</strong> {{ $companyName }}</p>
        <p style="margin: 0 0 8px;"><strong>Admin email:</strong> {{ $companyEmail }}</p>
        <p style="margin: 0 0 8px;"><strong>Order label:</strong> {{ $orderLabel ?: 'Not provided' }}</p>
        <p style="margin: 0 0 20px;"><strong>Quantity:</strong> {{ $quantity }}</p>

        <p style="margin: 0; color: #475569;">Please review this request and generate the card IDs for the company before closing the order.</p>
    </div>
</body>
</html>
