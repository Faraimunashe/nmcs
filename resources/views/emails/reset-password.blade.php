@extends('emails.layout')

@section('preheader')
    Use the link below to choose a new password for your NMCS Zimbabwe account.
@endsection

@section('content')
    <p style="margin:0 0 20px;font-size:16px;color:#334155;">
        Hello <strong style="color:#0f172a;">{{ $user->name }}</strong>,
    </p>
    <p style="margin:0 0 20px;">
        You are receiving this email because we received a <strong style="color:#065f46;">password reset request</strong> for your NMCS Zimbabwe conference account.
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 24px;border-collapse:collapse;">
        <tr>
            <td style="border-radius:10px;background-color:#059669;">
                <a href="{{ $resetUrl }}" style="display:inline-block;padding:14px 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;">
                    Reset password
                </a>
            </td>
        </tr>
    </table>
    <p style="margin:0 0 16px;font-size:13px;color:#64748b;">
        This link expires in <strong>{{ $expireMinutes }} minutes</strong>. If you did not request a reset, you can ignore this message and your password will stay the same.
    </p>
    <p style="margin:0;font-size:12px;color:#94a3b8;word-break:break-all;">
        If the button does not work, copy and paste this URL into your browser:<br>
        <a href="{{ $resetUrl }}" style="color:#047857;">{{ $resetUrl }}</a>
    </p>
    <p style="margin:28px 0 0;font-size:15px;color:#334155;">
        Kind regards,<br>
        <strong style="color:#065f46;">NMCS Zimbabwe</strong>
    </p>
@endsection
