<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $notification->title }}</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .notification-box {
            background: #f1f5f9;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .notification-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 10px 0;
        }
        .notification-message {
            color: #64748b;
            margin: 0;
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
        .button:hover {
            opacity: 0.9;
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
                <h1>TVET Revision</h1>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $user->name }},</p>

                <div class="notification-box">
                    <h2 class="notification-title">{{ $notification->title }}</h2>
                    <p class="notification-message">{{ $notification->message }}</p>
                </div>

                @if($notification->action_url)
                    <p style="text-align: center;">
                        <a href="{{ $notification->action_url }}" class="button">View Details</a>
                    </p>
                @endif

                <p style="margin-top: 30px; color: #64748b;">
                    If you have any questions, feel free to contact our support team.
                </p>
            </div>
            <div class="footer">
                <p>© {{ date('Y') }} TVET Revision. All rights reserved.</p>
                <p>
                    <a href="{{ route('student.notifications.preferences') }}">Manage notification preferences</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
