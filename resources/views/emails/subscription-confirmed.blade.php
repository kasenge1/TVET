<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmed</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .success-box {
            background: #d1fae5;
            border-left: 4px solid #10b981;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .details-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-table td:first-child {
            font-weight: 600;
            color: #64748b;
            width: 40%;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 20px;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div style="padding: 20px;">
        <div class="container">
            <div class="header">
                <div class="icon">&#10003;</div>
                <h1>Subscription Confirmed!</h1>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $user->name }},</p>

                <div class="success-box">
                    <p style="margin: 0; font-weight: 600; color: #065f46;">
                        Your premium subscription has been activated successfully!
                    </p>
                </div>

                <p>Thank you for subscribing to TVET Revision Premium. You now have access to all premium features including:</p>

                <ul style="color: #64748b;">
                    <li>Ad-free learning experience</li>
                    <li>Full access to all questions and answers</li>
                    <li>Progress tracking across all units</li>
                    <li>Bookmark unlimited questions</li>
                </ul>

                <h3 style="margin-top: 25px; color: #1e293b;">Subscription Details</h3>
                <table class="details-table">
                    <tr>
                        <td>Plan</td>
                        <td>{{ $subscription['package_name'] ?? 'Premium' }}</td>
                    </tr>
                    <tr>
                        <td>Amount Paid</td>
                        <td>KES {{ number_format($subscription['amount'] ?? 0) }}</td>
                    </tr>
                    <tr>
                        <td>Valid Until</td>
                        <td>{{ \Carbon\Carbon::parse($subscription['expires_at'])->format('F d, Y') }}</td>
                    </tr>
                    @if(isset($subscription['transaction_id']))
                    <tr>
                        <td>Transaction ID</td>
                        <td>{{ $subscription['transaction_id'] }}</td>
                    </tr>
                    @endif
                </table>

                <p style="text-align: center;">
                    <a href="{{ route('learn.index') }}" class="button">Start Learning Now</a>
                </p>

                <p style="margin-top: 30px; color: #64748b;">
                    If you have any questions about your subscription, feel free to contact our support team.
                </p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} TVET Revision. All rights reserved.</p>
                <p>
                    <a href="{{ route('learn.subscription') }}">Manage your subscription</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
