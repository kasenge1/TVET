<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
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
        .error-box {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
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
                <div class="icon">&#10060;</div>
                <h1>Payment Failed</h1>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $user->name }},</p>

                <div class="error-box">
                    <p style="margin: 0; font-weight: 600; color: #991b1b;">
                        Unfortunately, your payment could not be processed.
                    </p>
                    @if($reason)
                    <p style="margin: 10px 0 0; color: #7f1d1d; font-size: 14px;">
                        Reason: {{ $reason }}
                    </p>
                    @endif
                </div>

                <p>Don't worry! This can happen for several reasons:</p>

                <ul style="color: #64748b;">
                    <li>Insufficient funds in your M-Pesa account</li>
                    <li>Entered wrong PIN</li>
                    <li>Payment was cancelled</li>
                    <li>Network timeout</li>
                </ul>

                <p style="margin-top: 25px;">
                    Please try again when you're ready. Make sure you have sufficient balance and enter your M-Pesa PIN correctly.
                </p>

                <p style="text-align: center;">
                    <a href="{{ route('learn.subscription') }}" class="button">Try Again</a>
                </p>

                <p style="margin-top: 30px; color: #64748b; font-size: 14px;">
                    If you continue experiencing issues, please contact our support team for assistance.
                </p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} TVET Revision. All rights reserved.</p>
                <p>
                    <a href="{{ route('contact') }}">Contact Support</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
