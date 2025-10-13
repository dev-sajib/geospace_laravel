<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification Status</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #ef4444; padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">Account Verification Update</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #333333; margin-top: 0;">Verification Status</h2>
                            <p style="color: #666666; line-height: 1.6; font-size: 16px;">
                                Thank you for your interest in joining GeoSpace. After reviewing your application, we regret to inform you that we are unable to verify your account at this time.
                            </p>
                            
                            @if(isset($reason) && !empty($reason))
                            <div style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 15px; margin: 20px 0;">
                                <p style="margin: 0; color: #991b1b; font-size: 14px;">
                                    <strong>Reason:</strong><br>
                                    {{ $reason }}
                                </p>
                            </div>
                            @endif
                            
                            <p style="color: #666666; line-height: 1.6; font-size: 16px;">
                                If you believe this decision was made in error or if you would like to provide additional information, please contact our support team at <a href="mailto:support@geospace.com" style="color: #10b981; text-decoration: none;">support@geospace.com</a>.
                            </p>
                            
                            <p style="color: #666666; line-height: 1.6; font-size: 16px;">
                                We appreciate your understanding and wish you the best in your professional endeavors.
                            </p>
                            
                            <p style="color: #666666; line-height: 1.6; font-size: 16px; margin-bottom: 0;">
                                Best regards,<br>
                                <strong>The GeoSpace Team</strong>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                © 2025 GeoSpace. All rights reserved.<br>
                                This is an automated message, please do not reply to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
