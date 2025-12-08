<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expiring Soon</title>
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
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
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
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
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
                <div class="icon">&#9888;</div>
                <h1>Subscription Expiring Soon</h1>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $user->name }},</p>

                <div class="warning-box">
                    <p style="margin: 0; font-weight: 600; color: #92400e;">
                        Your premium subscription will expire on {{ \Carbon\Carbon::parse($subscription['expires_at'])->format('F d, Y') }}.
                    </p>
                </div>

                <p>Don't lose access to your premium benefits! Renew your subscription today to continue enjoying:</p>

                <ul style="color: #64748b;">
                    <li>Ad-free learning experience</li>
                    <li>Full access to all questions and answers</li>
                    <li>Progress tracking across all units</li>
                    <li>Bookmark unlimited questions</li>
                </ul>

                <p style="text-align: center;">
                    <a href="{{ route('learn.subscription') }}" class="button">Renew Subscription</a>
                </p>

                <p style="margin-top: 30px; color: #64748b; font-size: 14px;">
                    After your subscription expires, you'll still have access to free content, but premium features will no longer be available.
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
