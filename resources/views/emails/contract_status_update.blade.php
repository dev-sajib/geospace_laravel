<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contract Status Updated</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
   <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
      <tr>
         <td align="center">
            <table width="600" cellpadding="0" cellspacing="0"
               style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
               <!-- Header -->
               <tr>
                  <td style="background-color: #10b981; padding: 30px 40px; text-align: center;">
                     <h1 style="color: #ffffff; margin: 0; font-size: 28px;">Contract Status Updated</h1>
                  </td>
               </tr>

               <!-- Content -->
               <tr>
                  <td style="padding: 40px;">
                     <h2 style="color: #333333; margin-top: 0;">Hello {{ $freelancerName }}!</h2>
                     <p style="color: #666666; line-height: 1.6; font-size: 16px;">
                        We're writing to inform you that the status of your contract has been updated by {{ $companyName }}.
                     </p>

                     <!-- Contract Details -->
                     <div style="background-color: #f8f9fa; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0;">
                        <h3 style="color: #333333; margin-top: 0; margin-bottom: 15px;">Contract Details</h3>
                        <p style="margin: 8px 0; color: #555555;"><strong>Project:</strong> {{ $projectTitle }}</p>
                        <p style="margin: 8px 0; color: #555555;"><strong>Contract:</strong> {{ $contractTitle }}</p>
                        <p style="margin: 8px 0; color: #555555;"><strong>Company:</strong> {{ $companyName }}</p>
                     </div>

                     <!-- Status Change -->
                     <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0;">
                        <h3 style="color: #333333; margin-top: 0; margin-bottom: 15px;">Status Change</h3>
                        <p style="margin: 8px 0; color: #555555;">
                           <strong>Previous Status:</strong>
                           <span style="background-color: #e9ecef; padding: 2px 8px; border-radius: 4px; color: #495057;">{{ $oldStatus }}</span>
                        </p>
                        <p style="margin: 8px 0; color: #555555;">
                           <strong>New Status:</strong>
                           <span style="background-color: #d4edda; padding: 2px 8px; border-radius: 4px; color: #155724;">{{ $newStatus }}</span>
                        </p>
                     </div>

                     <!-- Status Explanations -->
                     @if($newStatus === 'Active')
                        <div style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 20px; margin: 20px 0;">
                           <p style="color: #0c5460; margin: 0; font-weight: 600;">
                              🎉 Great news! Your contract is now active. You can start working on the project.
                           </p>
                        </div>
                     @elseif($newStatus === 'Completed')
                        <div style="background-color: #d4edda; border-left: 4px solid #28a745; padding: 20px; margin: 20px 0;">
                           <p style="color: #155724; margin: 0; font-weight: 600;">
                              ✅ Congratulations! Your contract has been marked as completed.
                           </p>
                        </div>
                     @elseif($newStatus === 'Cancelled')
                        <div style="background-color: #f8d7da; border-left: 4px solid #dc3545; padding: 20px; margin: 20px 0;">
                           <p style="color: #721c24; margin: 0; font-weight: 600;">
                              ❌ Your contract has been cancelled. Please contact the company if you have any questions.
                           </p>
                        </div>
                     @elseif($newStatus === 'Disputed')
                        <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; margin: 20px 0;">
                           <p style="color: #856404; margin: 0; font-weight: 600;">
                              ⚠️ A dispute has been raised for this contract. Our support team will contact you soon.
                           </p>
                        </div>
                     @endif

                     <p style="color: #666666; line-height: 1.6; font-size: 16px; margin-top: 30px;">
                        You can view the full contract details and manage your work by logging into your GeoSpace dashboard.
                     </p>

                     <!-- CTA Button -->
                     <div style="text-align: center; margin: 30px 0;">
                        <a href="#"
                           style="background-color: #10b981; color: #ffffff; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                           View Contract Details
                        </a>
                     </div>

                     <p style="color: #666666; line-height: 1.6; font-size: 14px; margin-top: 30px;">
                        If you have any questions about this contract or need assistance, please don't hesitate to contact our support team.
                     </p>
                  </td>
               </tr>

               <!-- Footer -->
               <tr>
                  <td style="background-color: #f8f9fa; padding: 30px 40px; text-align: center; border-top: 1px solid #e9ecef;">
                     <p style="color: #6c757d; margin: 0; font-size: 14px;">
                        Best regards,<br>
                        The GeoSpace Team
                     </p>
                     <p style="color: #6c757d; margin: 10px 0 0 0; font-size: 12px;">
                        This is an automated email. Please do not reply to this message.
                     </p>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
   </table>
</body>

</html>