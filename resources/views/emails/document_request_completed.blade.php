<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Completed</title>
</head>

<body style="margin:0;padding:0;background:#f9fafb;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f9fafb;padding:40px 0;">
        <tr>
            <td align="center">

                <!-- Main Container -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border:1px solid #e5e7eb;">

                    <!-- Header -->

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding:24px 30px;border-bottom:1px solid #e5e7eb;">

                            <table cellpadding="0" cellspacing="0" align="center">
                                <tr>

                                    <!-- Icon -->
                                    <td style="vertical-align:middle;">
                                        <svg viewBox="0 0 24 24" width="40" height="40" fill="none">
                                            <path d="M6 5h8M6 5v14M6 11h6" stroke="#2563eb" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M18 7a5 5 0 100 10" stroke="#2563eb" stroke-width="2.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </td>

                                    <!-- Brand -->
                                    <td style="vertical-align:middle;padding-left:4px;">
                                        <div style="font-size:18px;font-weight:600;color:#2563eb;line-height:1;">
                                            FileCollect
                                        </div>

                                        <div style="font-size:11px;color:#2563eb;opacity:0.8;line-height:1;">
                                            Secure • Organize
                                        </div>
                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td align="center" style="padding:30px 0;">

                            <table width="540" cellpadding="0" cellspacing="0">

                                <tr>
                                    <td>

                                        <p style="margin:0 0 16px;color:#374151;font-size:14px;">
                                            Hello {{ $recipientName ?? 'User' }},
                                        </p>

                                        <p style="margin:0 0 16px;color:#374151;font-size:14px;">
                                            <strong>{{ $clientName }}</strong> has completed uploading all required
                                            documents.
                                        </p>

                                        <!-- Highlight Box -->
                                        <table width="100%" cellpadding="0" cellspacing="0"
                                            style="background:#ecfdf5;border:1px solid #d1fae5;margin-bottom:20px;">
                                            <tr>
                                                <td style="padding:14px;font-size:13px;color:#065f46;">

                                                    <strong>Completion Details</strong><br><br>

                                                    <strong>Request ID:</strong> {{ $requestNumber }}<br>
                                                    <strong>Status:</strong> Completed

                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin:0 0 20px;color:#374151;font-size:14px;">
                                            You can now review all submitted documents using the link below.
                                        </p>

                                        <!-- Button -->
                                        <table cellpadding="0" cellspacing="0" style="margin-bottom:30px;">
                                            <tr>
                                                <td align="center" bgcolor="#2563eb">
                                                    <a href="{{ $url }}"
                                                        style="display:inline-block;padding:12px 22px;
        font-size:14px;color:#ffffff;text-decoration:none;font-weight:600;">
                                                        View Completed Request
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <!-- Fallback -->
                                        <p style="margin:20px 0;color:#374151;font-size:13px;">
                                            If the button above does not work, copy and paste this URL:
                                        </p>

                                        <p style="word-break:break-all;color:#2563eb;font-size:13px;">
                                            {{ $url }}
                                        </p>

                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding:20px 0;border-top:1px solid #e5e7eb;">

                            <table width="540" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="font-size:12px;color:#6b7280;">

                                        <p style="margin:0 0 8px;">
                                            This is an automated notification from FileCollect.
                                        </p>

                                        <p style="margin:0;">
                                            © {{ now()->year }} FileCollect. All rights reserved.
                                        </p>

                                        <p style="margin-top:8px;">
                                            Support: {{ config('mail.from.address') }}
                                        </p>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
