<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Paper Has Been Cited</title>
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
            max-width: 600px;
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
            margin-bottom: 20px;
            text-align: center;
        }
        .info-card {
            background-color: #f9fafb;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 30px;
            border: 1px solid #f3f4f6;
        }
        .info-item {
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 14px;
            min-width: 120px;
        }
        .info-value {
            font-weight: 700;
            color: #111827;
            text-align: right;
            font-size: 14px;
        }
        .payment-info {
            background-color: #eff6ff;
            border: 1px dashed #3b82f6;
            border-radius: 16px;
            padding: 20px;
            margin-top: 30px;
            text-align: center;
        }
        .upi-id {
            display: inline-block;
            background-color: #ffffff;
            padding: 8px 16px;
            border-radius: 8px;
            color: #2563eb;
            font-weight: 700;
            font-size: 16px;
            margin-top: 10px;
            border: 1px solid #dbeafe;
        }
        .footer {
            padding: 24px;
            background-color: #f9fafb;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }
        .emphasis {
            color: #2563eb;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Citation Hub</h1>
        </div>
        <div class="content">
            <h2>Citation Claim Requested</h2>
            <p>Hello <span class="emphasis">{{ $funder->first_name }}</span>,</p>
            <p>A researcher has cited your work and submitted a claim request. To verify this citation and process the reward, a platform fee of ₹100 is required.</p>
            
            <div class="info-card">
                <div class="info-item">
                    <span class="info-label">Paper Title:</span>
                    <span class="info-value">{{ $paper->title }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Cited By:</span>
                    <span class="info-value">{{ $citer->first_name }} {{ $citer->last_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Platform Fee:</span>
                    <span class="info-value">₹100.00</span>
                </div>
            </div>

            <p>Please <span class="emphasis">pay ₹100 manually</span> to the Admin UPI ID below to process this citation.</p>

            <div class="payment-info">
                <p style="margin-top: 0; font-weight: 600; color: #1e40af;">Payment Details (UPI)</p>
                <div class="upi-id">madhusujan593@okaxis</div>
                <p style="font-size: 12px; margin-top: 10px; color: #6b7280;">Please share the transaction screenshot with the admin for faster approval.</p>
            </div>

            <p style="margin-top: 30px;">Keep up the great work! Your contributions are making an impact.</p>
            
            <p>Best regards,<br>The Citation Hub Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Citation Hub. All rights reserved.
        </div>
    </div>
</body>
</html>
