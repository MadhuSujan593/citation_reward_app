<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResearchHub Password Reset</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f3f4f6;
            color: #374151;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 550px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #3b82f6;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .content {
            padding: 40px;
        }
        .content h2 {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 16px;
        }
        .content p {
            margin-bottom: 20px;
            font-size: 15px;
        }
        .button-container {
            text-align: center;
            margin: 32px 0;
        }
        .button {
            background-color: #3b82f6;
            color: #ffffff !important;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            display: inline-block;
        }
        .footer {
            padding: 24px;
            background-color: #f9fafb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .link-alt {
            word-break: break-all;
            color: #9ca3af;
            font-size: 11px;
            border-top: 1px solid #f3f4f6;
            margin-top: 30px;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ResearchHub</h1>
        </div>
        <div class="content">
            <h2>Reset Your Password</h2>
            <p>Hello {{ $user->first_name }},</p>
            <p>We received a request to reset your password. No worries, we've got you covered. Just click the button below to get back into your account:</p>
            
            <div class="button-container">
                <a href="{{ $url }}" class="button">Reset My Password</a>
            </div>
            
            <p>This link will expire in 60 minutes for your security. If you didn't request this, you can safely ignore this email.</p>
            
            <p>Thanks,<br>The ResearchHub Team</p>
            
            <div class="link-alt">
                Having trouble? Paste this link into your browser:<br>
                <a href="{{ $url }}" style="color: #3b82f6; text-decoration: none;">{{ $url }}</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} ResearchHub. All rights reserved.
        </div>
    </div>
</body>
</html>
