<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Deadline Reminder</title>
</head>
<body style="margin:0; padding:0; background:#f5f7fb; font-family: Arial, sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb; padding:24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 25px rgba(17,24,39,0.08);">
                    <tr>
                        <td style="background:#1e3a5f; color:#ffffff; padding:24px 32px;">
                            <h1 style="margin:0; font-size:20px; font-weight:700;">Submission Deadline and Final Closure (Comments)</h1>
                            <p style="margin:6px 0 0; font-size:14px; opacity:0.85;">University Ideas System</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;">
                            <p style="margin:0 0 12px; font-size:15px;">Hello {{ $staff->name }},</p>
                            <p style="margin:0 0 16px; font-size:15px; line-height:1.6;">
                                This is a friendly reminder from {{ $coordinator->name }} regarding the upcoming
                                idea submission and comment closure deadlines for the {{ $department->name }} department.
                                Please make sure to submit your ideas and comments before the deadlines below.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:10px; margin:18px 0;">
                                <tr>
                                    <td style="padding:16px 18px; font-size:14px;">
                                        <strong>Submission Deadline:</strong>
                                        <span style="margin-left:6px;">
                                            {{ $ideaClosureDate ? $ideaClosureDate->format('M d, Y') : 'To be announced' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0 18px 16px; font-size:14px;">
                                        <strong>Final Closure (Comments):</strong>
                                        <span style="margin-left:6px;">
                                            {{ $finalClosureDate ? $finalClosureDate->format('M d, Y') : 'To be announced' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0; font-size:14px; line-height:1.6;">
                                Your contribution helps improve our university. Thank you for participating!
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 32px; background:#f8fafc; color:#6b7280; font-size:12px;">
                            This email was sent by the University Ideas System on behalf of your QA Coordinator.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
