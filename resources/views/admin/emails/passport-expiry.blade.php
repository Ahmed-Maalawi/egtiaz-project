<!DOCTYPE html>
<html>
<head>
    <title>Passport Expiry Alert</title>
    <style>
        .alert { color: #856404; background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; }
        .info-box { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
<h2>⚠️ Passport Expiry Alert</h2>

<p>Hello {{ $moderatorName }},</p>

<div class="alert">
    <strong>Important:</strong> An employee's passport is expiring soon!
</div>

<div class="info-box">
    <h3>Employee Details:</h3>
    <ul>
        <li><strong>Employee Name:</strong> {{ $employeeName }}</li>
        <li><strong>Passport Number:</strong> {{ $passportNumber }}</li>
        <li><strong>Expiry Date:</strong> {{ $expiryDate }}</li>
        <li><strong>Days Until Expiry:</strong> {{ $daysUntilExpiry }} days</li>
        <li><strong>Company:</strong> {{ $companyName }}</li>
    </ul>
</div>

<p><strong>Action Required:</strong> Please ensure the employee renews their passport before the expiry date to avoid any legal or travel complications.</p>

<p>This is an automated notification. Please take appropriate action.</p>

<br>
<p>Best regards,<br>
    {{ config('app.name') }} System</p>
</body>
</html>
