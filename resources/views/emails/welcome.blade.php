<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TVET Revision</title>
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
            font-size: 28px;
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
            font-size: 20px;
            margin-bottom: 20px;
            color: #1e293b;
        }
        .feature-box {
            background: #eff6ff;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .feature-item:last-child {
            margin-bottom: 0;
        }
        .feature-icon {
            background: #3b82f6;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
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
                <div class="icon">&#127891;</div>
                <h1>Welcome to TVET Revision!</h1>
            </div>
            <div class="content">
                <p class="greeting">Hello {{ $user->name }},</p>

                <p>Congratulations on joining TVET Revision! We're excited to help you on your journey to exam success.</p>

                <p style="font-weight: 600; color: #1e293b; margin-top: 25px;">Here's what you can do on our platform:</p>

                <div class="feature-box">
                    <div class="feature-item">
                        <div class="feature-icon">&#10003;</div>
                        <div>
                            <strong>Access Past Papers</strong>
                            <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">Browse thousands of KNEC exam questions organized by course and unit</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">&#10003;</div>
                        <div>
                            <strong>Study Answers</strong>
                            <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">Get detailed answers to help you understand each topic</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">&#10003;</div>
                        <div>
                            <strong>Track Progress</strong>
                            <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">Monitor your study progress across all units</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">&#10003;</div>
                        <div>
                            <strong>Bookmark Questions</strong>
                            <p style="margin: 5px 0 0; color: #64748b; font-size: 14px;">Save important questions for quick revision later</p>
                        </div>
                    </div>
                </div>

                <p style="text-align: center; margin-top: 30px;">
                    <a href="{{ route('learn.index') }}" class="button">Start Learning Now</a>
                </p>

                <p style="margin-top: 30px; color: #64748b; font-size: 14px; text-align: center;">
                    Need help? Check out our <a href="{{ route('faq') }}">FAQ page</a> or contact our support team.
                </p>
            </div>
            <div class="footer">
                <p>&copy; {{ date('Y') }} TVET Revision. All rights reserved.</p>
                <p>Your comprehensive platform for TVET exam preparation</p>
            </div>
        </div>
    </div>
</body>
</html>
