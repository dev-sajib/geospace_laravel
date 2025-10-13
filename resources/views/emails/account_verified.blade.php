<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verified</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #10b981; padding: 30px 40px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">🎉 Account Verified!</h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #333333; margin-top: 0;">Congratulations!</h2>
                            <p style="color: #666666; line-height: 1.6; font-size: 16px;">
                                Your GeoSpace account has been successfully verified and activated.
                            </p>
                            <p style="color: #666666; line-height: 1.6; font-size: 16px;">
                                You can now log in to your account and start exploring all the features GeoSpace has to offer.
                            </p>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ env('FRONTEND_URL') }}/login" style="display: inline-block; background-color: #10b981; color: #ffffff; padding: 15px 40px; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: bold;">
                                    Log In Now
                                </a>
                            </div>
                            
                            <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 15px; margin: 20px 0;">
                                <p style="margin: 0; color: #065f46; font-size: 14px;">
                                    <strong>Next Steps:</strong><br>
                                    • Complete your profile<br>
                                    • Browse available projects<br>
                                    • Connect with clients/freelancers<br>
                                    • Start collaborating!
                                </p>
                            </div>
                            
                            <p style="color: #666666; line-height: 1.6; font-size: 16px;">
                                If you have any questions or need assistance, our support team is here to help.
                            </p>
                            
                            <p style="color: #666666; line-height: 1.6; font-size: 16px; margin-bottom: 0;">
                                Welcome to GeoSpace!<br>
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
