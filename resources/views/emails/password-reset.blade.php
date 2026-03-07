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
    <title>Reset Your Password</title>
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
            max-width: 550px !important;
            margin: 0 auto;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: #ffffff !important;
            padding: 14px 28px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            mso-padding-alt: 0;
        }
        @media only screen and (max-width: 550px) {
            .mobile-padding {
                padding: 24px !important;
            }
            .button {
                display: block !important;
                text-align: center !important;
            }
        }
    </style>
</head>
<body style="font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f3f4f6; color: #374151; margin: 0; padding: 0;">
    <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <table role="presentation" class="container" width="550" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #3b82f6; padding: 30px;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 800; letter-spacing: -0.025em;">Citation Hub</h1>
                        </td>
                    </tr>
                    <!-- Content -->
                    <tr>
                        <td class="mobile-padding" style="padding: 40px;">
                            <h2 style="font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 16px 0;">Reset Your Password</h2>
                            
                            <p style="margin: 0 0 15px 0; font-size: 16px;">Hello {{ $user->first_name }},</p>
                            <p style="margin: 0 0 25px 0; font-size: 16px; line-height: 1.6;">We received a request to reset your password. No worries, we've got you covered. Just click the button below to get back into your account:</p>
                            
                            <!-- Button -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <!--[if mso]>
                                        <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $url }}" style="height:50px;v-text-anchor:middle;width:200px;" arcsize="24%" stroke="f" fillcolor="#3b82f6">
                                            <w:anchorlock/>
                                            <center style="color:#ffffff;font-family:sans-serif;font-size:15px;font-weight:bold;">Reset My Password</center>
                                        </v:roundrect>
                                        <![endif]-->
                                        <!--[if !mso]><!-->
                                        <a href="{{ $url }}" class="button" style="background-color: #3b82f6; color: #ffffff !important; padding: 14px 28px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 15px; display: inline-block;">Reset My Password</a>
                                        <!--<![endif]-->
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 0 0 20px 0; font-size: 15px; color: #6b7280; line-height: 1.6;">This link will expire in 60 minutes for your security. If you didn't request this, you can safely ignore this email.</p>
                            
                            <p style="margin: 0; font-size: 16px; line-height: 1.6;">Thanks,<br><strong style="color: #111827;">The Citation Hub Team</strong></p>
                            
                            <!-- Link Alternative -->
                            <table role="presentation" width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top: 30px; border-top: 1px solid #f3f4f6; padding-top: 20px;">
                                <tr>
                                    <td>
                                        <p style="margin: 0; font-size: 12px; color: #9ca3af; line-height: 1.5; word-break: break-all;">
                                            Having trouble? Paste this link into your browser:<br>
                                            <a href="{{ $url }}" style="color: #3b82f6; text-decoration: none;">{{ $url }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
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
