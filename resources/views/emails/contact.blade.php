<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Message</title>
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
        .message-box {
            background: #f1f5f9;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-row {
            margin-bottom: 15px;
        }
        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            color: #1e293b;
            font-size: 16px;
            margin-top: 4px;
        }
        .message-content {
            white-space: pre-wrap;
            color: #374151;
            line-height: 1.8;
        }
        .footer {
            background: #f8fafc;
            padding: 20px 30px;
            text-align: center;
            color: #64748b;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
        }
        .reply-note {
            background: #dbeafe;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            color: #1e40af;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div style="padding: 20px;">
        <div class="container">
            <div class="header">
                <h1>New Contact Form Message</h1>
            </div>
            <div class="content">
                <p>You have received a new message from the contact form on your website.</p>

                <div class="info-row">
                    <div class="info-label">From</div>
                    <div class="info-value">{{ $name }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value"><a href="mailto:{{ $email }}">{{ $email }}</a></div>
                </div>

                <div class="info-row">
                    <div class="info-label">Subject</div>
                    <div class="info-value">{{ $subject }}</div>
                </div>

                <div class="message-box">
                    <div class="info-label">Message</div>
                    <div class="message-content">{{ $messageContent }}</div>
                </div>

                <div class="reply-note">
                    <strong>Note:</strong> You can reply directly to this email to respond to {{ $name }}.
                </div>
            </div>
            <div class="footer">
                <p>This message was sent from {{ config('app.name') }} contact form.</p>
                <p>{{ now()->format('F j, Y \a\t g:i A') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
