<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
</head>
<body>
<h2>Payment Received Successfully</h2>

<p>Hello {{ $moderatorName }},</p>

<p>We're pleased to inform you that a payment has been successfully processed for your company <strong>{{ $companyName }}</strong>.</p>

<div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0;">
    <h3>Payment Details:</h3>
    <ul>
        <li><strong>Amount:</strong> {{ $amount }} {{ $currency }}</li>
        <li><strong>Transaction ID:</strong> {{ $transactionId }}</li>
        <li><strong>Payment Date:</strong> {{ $paymentDate->format('F j, Y \a\t g:i A') }}</li>
        <li><strong>New Wallet Balance:</strong> {{ $newBalance }} {{ $currency }}</li>
    </ul>
</div>

<p>The amount has been successfully credited to your company's wallet and is now available for use.</p>

<p>If you have any questions or concerns, please contact our support team.</p>

<br>
<p>Best regards,<br>
    {{ config('app.name') }}</p>
</body>
</html>
