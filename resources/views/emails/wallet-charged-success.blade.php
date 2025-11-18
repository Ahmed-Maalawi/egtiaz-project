<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - {{ config('app.name') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .email-header p {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 400;
        }

        .email-body {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 24px;
        }

        .payment-card {
            background: linear-gradient(135deg, #f7f9fc 0%, #f1f5f9 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            border-left: 4px solid #10b981;
        }

        .payment-card h3 {
            color: #1e293b;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .payment-card h3:before {
            content: "💳";
            font-size: 24px;
        }

        .payment-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .detail-item {
            margin-bottom: 12px;
        }

        .detail-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            display: block;
            margin-bottom: 4px;
        }

        .detail-value {
            font-size: 16px;
            color: #1e293b;
            font-weight: 600;
        }

        .amount-highlight {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 25px 0;
        }

        .amount-number {
            font-size: 32px;
            font-weight: 700;
            display: block;
        }

        .amount-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .balance-update {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
        }

        .balance-amount {
            font-size: 24px;
            font-weight: 700;
            color: #1d4ed8;
            display: block;
        }

        .action-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }

        .support-section {
            background: #f8fafc;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
            border-top: 3px solid #e2e8f0;
        }

        .support-contact {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
        }

        .email-footer {
            background: #1e293b;
            color: #94a3b8;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }

        .footer-links {
            margin: 15px 0;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            margin: 0 10px;
        }

        .company-name {
            color: #ffffff;
            font-weight: 600;
            font-size: 16px;
        }

        @media (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }

            .payment-details {
                grid-template-columns: 1fr;
            }

            .payment-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Header -->
    <div class="email-header">
        <h1>Payment Received! 🎉</h1>
        <p>Your wallet has been successfully charged</p>
    </div>

    <!-- Body -->
    <div class="email-body">
        <p class="greeting">Hello <strong>{{ $moderatorName }}</strong>,</p>

        <p>We're pleased to inform you that a payment has been successfully processed for your company <strong>{{ $companyName }}</strong> and the amount has been credited to your company wallet.</p>

        <!-- Amount Highlight -->
        <div class="amount-highlight">
            <span class="amount-number">{{ $amount }} {{ $currency }}</span>
            <span class="amount-label">Payment Amount</span>
        </div>

        <!-- Payment Details Card -->
        <div class="payment-card">
            <h3>Transaction Details</h3>
            <div class="payment-details">
                <div class="detail-item">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value">#{{ $transactionId }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Date</span>
                    <span class="detail-value">{{ $paymentDate->format('F j, Y \a\t g:i A') }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Payment Method</span>
                    <span class="detail-value">Online Payment</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #10b981;">● Completed</span>
                </div>
            </div>
        </div>

        <!-- Balance Update -->
        <div class="balance-update">
            <span class="detail-label">New Wallet Balance</span>
            <span class="balance-amount">{{ $newBalance }} {{ $currency }}</span>
            <p style="margin-top: 8px; color: #475569; font-size: 14px;">Your wallet has been updated successfully</p>
        </div>

        <!-- Support Section -->
        <div class="support-section">
            <h4 style="margin-bottom: 10px; color: #1e293b;">Need Help?</h4>
            <p style="color: #64748b; margin-bottom: 15px;">If you have any questions about this transaction, our support team is here to help.</p>
            <a href="mailto:support@{{ config('app.domain', 'example.com') }}" class="support-contact">
                Contact Support ›
            </a>
        </div>
    </div>

    <!-- Footer -->
    <div class="email-footer">
        <div class="company-name">{{ config('app.name') }}</div>
        <p style="margin: 10px 0; color: #94a3b8;">Simplifying your business transactions</p>

        <div class="footer-links">
            <a href="{{ url('/privacy') }}">Privacy Policy</a>
            <a href="{{ url('/terms') }}">Terms of Service</a>
            <a href="{{ url('/contact') }}">Contact Us</a>
        </div>

        <p style="margin-top: 20px; font-size: 12px; color: #64748b;">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            This is an automated transaction notification. Please do not reply to this email.
        </p>
    </div>
</div>
</body>
</html>
