<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expired</title>
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
            background: linear-gradient(135deg, #6b7280 0%, #9ca3af 100%);
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
        .info-box {
            background: #f1f5f9;
            border-left: 4px solid #6b7280;
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
                <div class="icon">&#128274;</div>
                <h1>Subscription Expired</h1>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $user->name }},</p>

                <div class="info-box">
                    <p style="margin: 0; color: #475569;">
                        Your premium subscription has expired. You've been moved to the free tier.
                    </p>
                </div>

                <p>We're sad to see you go from premium! As a free user, you can still:</p>

                <ul style="color: #64748b;">
                    <li>Access all questions and answers</li>
                    <li>Track your learning progress</li>
                    <li>Bookmark questions (limited)</li>
                </ul>

                <p>However, you'll now see advertisements while using the platform.</p>

                <p style="margin-top: 25px; font-weight: 600; color: #1e293b;">
                    Ready to get premium benefits back?
                </p>

                <p style="text-align: center;">
                    <a href="{{ route('learn.subscription') }}" class="button">Reactivate Premium</a>
                </p>

                <p style="margin-top: 30px; color: #64748b; font-size: 14px;">
                    If you have any questions about your subscription or need assistance, please contact our support team.
                </p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} TVET Revision. All rights reserved.</p>
                <p>
                    <a href="{{ route('learn.subscription') }}">View subscription plans</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
