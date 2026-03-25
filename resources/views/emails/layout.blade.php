<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', 'NMCS Zimbabwe')</title>
</head>
<body style="margin:0;padding:0;background-color:#f0fdf4;-webkit-font-smoothing:antialiased;">
    @hasSection('preheader')
        <div style="display:none;max-height:0;overflow:hidden;mso-hide:all;font-size:1px;line-height:1px;color:#f0fdf4;opacity:0;">
            @yield('preheader')
        </div>
    @endif

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color:#f0fdf4;border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:32px 16px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" style="max-width:600px;width:100%;border-collapse:collapse;background-color:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 10px 40px rgba(15,118,110,0.08);border:1px solid #d1fae5;">
                    {{-- Header --}}
                    <tr>
                        <td bgcolor="#ffffff" style="padding:28px 32px 24px;text-align:center;background-color:#ecfdf5;background:linear-gradient(180deg,#ffffff 0%,#ecfdf5 100%);border-bottom:1px solid #d1fae5;">
                            @if(file_exists(public_path('images/nmcs.jpeg')))
                                <table role="presentation" cellspacing="0" cellpadding="0" align="center" style="margin:0 auto;border-collapse:collapse;">
                                    <tr>
                                        <td style="padding:10px 14px;background-color:#ffffff;border-radius:14px;border:1px solid #d1fae5;box-shadow:0 1px 2px rgba(0,0,0,0.04);">
                                            <img src="{{ $message->embed(public_path('images/nmcs.jpeg')) }}" alt="NMCS Zimbabwe" width="88" height="auto" style="display:block;width:88px;max-width:88px;height:auto;border:0;outline:none;text-decoration:none;">
                                        </td>
                                    </tr>
                                </table>
                            @endif
                            <p style="margin:18px 0 0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;font-size:20px;font-weight:600;color:#065f46;letter-spacing:-0.02em;">
                                NMCS Zimbabwe
                            </p>
                            <p style="margin:6px 0 0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;font-size:13px;color:#6b7280;line-height:1.4;">
                                Easter Conference Registration
                            </p>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td style="padding:36px 40px 8px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;font-size:16px;line-height:1.65;color:#334155;">
                            @yield('content')
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding:28px 40px 36px;border-top:1px solid #e5e7eb;background-color:#fafafa;">
                            <p style="margin:0 0 12px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;font-size:13px;line-height:1.5;color:#64748b;text-align:center;">
                                This message was sent by the NMCS Zimbabwe conference system.
                            </p>
                            <p style="margin:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;font-size:12px;line-height:1.5;color:#94a3b8;text-align:center;">
                                &copy; {{ date('Y') }} NMCS Zimbabwe. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
                <p style="margin:20px 0 0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,'Helvetica Neue',Arial,sans-serif;font-size:11px;color:#94a3b8;text-align:center;max-width:480px;">
                    If you did not expect this email, you can safely ignore it or contact the conference organisers.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
