<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--[if mso]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <title>Your Paper Has Been Cited</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            background-color: #f3f4f6;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            border: 0;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        .container {
            width: 100%;
            max-width: 600px !important;
            margin: 0 auto;
        }
        @media only screen and (max-width: 600px) {
            .full-width {
                width: 100% !important;
            }
            .mobile-padding {
                padding: 20px !important;
            }
            .info-label, .info-value {
                display: block !important;
                width: 100% !important;
                text-align: left !important;
            }
            .info-value {
                margin-top: 4px !important;
                margin-bottom: 12px !important;
            }
        }
    </style>
</head>
<body style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f3f4f6; color: #374151; margin: 0; padding: 0;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <table role="presentation" class="container" width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #3b82f6; padding: 30px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.025em;">Citation Hub</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td class="mobile-padding" style="padding: 40px;">
                            <h2 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 20px 0; text-align: center;">Citation Claim Requested</h2>
                            
                            <p style="margin: 0 0 15px 0; font-size: 16px;">Hello <span style="color: #2563eb; font-weight: 700;">{{ $funder->first_name }}</span>,</p>
                            <p style="margin: 0 0 15px 0; font-size: 16px;">Thank you for using our platform.</p>
                            <p style="margin: 0 0 30px 0; font-size: 16px;">We truly appreciate your support. Kindly proceed with donating the amount to the following account at your earliest convenience.</p>
                            
                            <!-- Info Card -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f9fafb; border-radius: 16px; border: 1px solid #f3f4f6; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td class="info-label" width="120" valign="top" style="font-weight: 600; color: #6b7280; font-size: 14px;">Paper Title:</td>
                                                            <td class="info-value" align="right" style="font-weight: 700; color: #111827; font-size: 14px;">{{ $paper->title }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-bottom: 12px;">
                                                    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td class="info-label" width="120" valign="top" style="font-weight: 600; color: #6b7280; font-size: 14px;">Cited By:</td>
                                                            <td class="info-value" align="right" style="font-weight: 700; color: #111827; font-size: 14px;">{{ $citer->first_name }} {{ $citer->last_name }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                            <td class="info-label" width="120" valign="top" style="font-weight: 600; color: #6b7280; font-size: 14px;">Platform Fee:</td>
                                                            <td class="info-value" align="right" style="font-weight: 700; color: #111827; font-size: 14px;">₹{{ number_format($amount, 2) }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Payment Info -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #eff6ff; border: 1px dashed #3b82f6; border-radius: 16px;">
                                <tr>
                                    <td align="center" style="padding: 20px;">
                                        <p style="margin: 0 0 15px 0; font-weight: 600; color: #1e40af; font-size: 16px;">Payment Details</p>
                                        
                                        <div style="background-color: #ffffff; padding: 10px 20px; border-radius: 10px; border: 1px solid #dbeafe; display: inline-block; margin-bottom: 10px;">
                                            <span style="color: #2563eb; font-weight: 700; font-size: 16px;">UPI ID: 9985327199@ybl</span>
                                        </div>
                                        <br>
                                        <div style="background-color: #ffffff; padding: 10px 20px; border-radius: 10px; border: 1px solid #dbeafe; display: inline-block;">
                                            <span style="color: #2563eb; font-weight: 700; font-size: 16px;">PhonePe/GPay: 9985327199</span>
                                        </div>
                                        
                                        <p style="margin: 15px 0 0 0; font-size: 12px; color: #6b7280; font-weight: 500;">Please share the transaction screenshot with the admin for faster approval.</p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 30px 0 15px 0; font-size: 15px;">If you require any further details or assistance regarding the donation process, please feel free to contact us.</p>
                            <p style="margin: 0 0 30px 0; font-size: 15px;">Thank you once again for your valuable contribution.</p>
                            
                            <p style="margin: 0; font-size: 15px;">Best regards,<br><strong style="color: #111827;">The Citation Hub Team</strong></p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f9fafb; padding: 24px; border-top: 1px solid #f3f4f6;">
                            <p style="margin: 0; font-size: 12px; color: #6b7280;">&copy; {{ date('Y') }} Citation Hub. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
