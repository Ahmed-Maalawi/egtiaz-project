<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Papers Completed</title>
</head>

<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">
                                🎉 All Papers Completed Successfully!
                            </h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px; font-size: 16px; color: #333333; line-height: 1.6;">
                                Dear <strong>{{ $moderatorName }}</strong>,
                            </p>

                            <p style="margin: 0 0 30px; font-size: 16px; color: #333333; line-height: 1.6;">
                                We are pleased to inform you that all required papers for employee
                                <strong>{{ $employeeName }}</strong> have been completed.
                            </p>

                            <!-- Summary Information -->
                            <table width="100%" cellpadding="0" cellspacing="0"
                                style="background-color: #f8f9fa; border-radius: 6px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <h2
                                            style="margin: 0 0 15px; font-size: 20px; color: #333333; font-weight: bold;">
                                            Summary Information
                                        </h2>
                                        <table width="100%" cellpadding="8" cellspacing="0">
                                            <tr>
                                                <td style="font-size: 15px; color: #666666; padding: 8px 0;">
                                                    <strong>Employee Name:</strong>
                                                </td>
                                                <td style="font-size: 15px; color: #333333; padding: 8px 0;">
                                                    {{ $employeeName }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 15px; color: #666666; padding: 8px 0;">
                                                    <strong>Company:</strong>
                                                </td>
                                                <td style="font-size: 15px; color: #333333; padding: 8px 0;">
                                                    {{ $companyName }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 15px; color: #666666; padding: 8px 0;">
                                                    <strong>Total Stages Completed:</strong>
                                                </td>
                                                <td style="font-size: 15px; color: #333333; padding: 8px 0;">
                                                    {{ $totalStages }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 15px; color: #666666; padding: 8px 0;">
                                                    <strong>Completion Date:</strong>
                                                </td>
                                                <td style="font-size: 15px; color: #333333; padding: 8px 0;">
                                                    {{ $completedAt }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Completed Stages -->
                            <h2 style="margin: 0 0 15px; font-size: 20px; color: #333333; font-weight: bold;">
                                Completed Stages
                            </h2>
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 30px;">
                                @foreach($completedStages as $stage)
                                    <tr>
                                        <td
                                            style="padding: 12px; background-color: #f8f9fa; border-left: 4px solid #28a745; margin-bottom: 10px; border-radius: 4px;">
                                            <div style="font-size: 16px; color: #333333; margin-bottom: 4px;">
                                                <span style="color: #28a745; margin-right: 8px;">✅</span>
                                                <strong>{{ $stage->stage->name }}</strong>
                                            </div>
                                            <div style="font-size: 14px; color: #666666;">
                                                Completed on: {{ $stage->completed_at->format('d M Y, h:i A') }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="height: 10px;"></td>
                                    </tr>
                                @endforeach
                            </table>

                            <p style="margin: 0 0 20px; font-size: 16px; color: #333333; line-height: 1.6;">
                                If you have any questions, feel free to contact us.
                            </p>

                            <p style="margin: 0; font-size: 16px; color: #333333; line-height: 1.6;">
                                Thanks,<br>
                                <strong>{{ config('app.name') }} Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f8f9fa; padding: 20px 30px; text-align: center; border-top: 1px solid #e9ecef;">
                            <p style="margin: 0; font-size: 12px; color: #999999;">
                                This is an automated message. Please do not reply.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>