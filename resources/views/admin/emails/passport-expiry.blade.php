<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Passport Expiry Alert</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 25px;
            margin: auto;
            border-radius: 8px;
            border: 1px solid #e6e6e6;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        h2 {
            font-size: 22px;
            color: #333333;
            margin-bottom: 10px;
        }

        p {
            font-size: 15px;
            color: #444;
            line-height: 1.6;
        }

        .alert-box {
            background: #fff4e5;
            border-left: 5px solid #ffa200;
            padding: 12px 15px;
            margin: 15px 0;
            border-radius: 4px;
            color: #664d03;
            font-size: 14px;
        }

        .details-box {
            background: #fafafa;
            padding: 18px;
            border-radius: 6px;
            border: 1px solid #e8e8e8;
            margin: 20px 0;
        }

        .details-box h3 {
            font-size: 17px;
            color: #333;
            margin-bottom: 10px;
        }

        ul {
            padding-left: 18px;
        }

        li {
            margin-bottom: 6px;
            font-size: 14px;
            color: #444;
        }

        .footer {
            margin-top: 25px;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>⚠️ Passport Expiry Alert</h2>

    <p>Hello {{ $moderatorName }},</p>

    <div class="alert-box">
        <strong>Important:</strong> An employee's passport is nearing expiry.
    </div>

    <div class="details-box">
        <h3>Employee Details</h3>
        <ul>
            <li><strong>Employee Name:</strong> {{ $employeeName }}</li>
            <li><strong>Passport Number:</strong> {{ $passportNumber }}</li>
            <li><strong>Expiry Date:</strong> {{ $expiryDate }}</li>
            <li><strong>Days Until Expiry:</strong> {{ $daysUntilExpiry }} days</li>
            <li><strong>Company:</strong> {{ $companyName }}</li>
        </ul>
    </div>

    <p><strong>Action Required:</strong> Please ensure the employee renews their passport before the expiry date to avoid legal or travel issues.</p>

    <p>This is an automated message. Please take the necessary action.</p>

    <p class="footer">
        Best regards,<br>
        {{ config('app.name') }} System
    </p>

</div>

</body>
</html>
