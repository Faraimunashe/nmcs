@extends('emails.layout')

@section('preheader')
    We received the student profile for {{ trim($student->firstnames.' '.$student->surname) }}.
@endsection

@section('content')
    <p style="margin:0 0 20px;font-size:16px;color:#334155;">
        Hello <strong style="color:#0f172a;">{{ $student->user->name ?? 'there' }}</strong>,
    </p>

    <p style="margin:0 0 20px;">
        Thank you for registering your personal information for the <strong style="color:#065f46;">NMCS Easter Conference</strong>.
        We have successfully received the profile for:
    </p>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;border-collapse:collapse;background-color:#ecfdf5;border-radius:12px;border:1px solid #a7f3d0;">
        <tr>
            <td style="padding:18px 20px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
                <p style="margin:0;font-size:15px;font-weight:600;color:#065f46;">
                    {{ trim($student->firstnames.' '.$student->surname) }}
                </p>
                <p style="margin:6px 0 0;font-size:13px;color:#047857;">
                    Attendant record
                </p>
            </td>
        </tr>
    </table>

    <p style="margin:0 0 20px;">
        Your information will be handled with care and confidentiality. We are committed to applying proper data protection principles, and the information you provide will be used only for legitimate conference-related purposes, stored securely, and processed ethically and responsibly.
    </p>

    <p style="margin:0 0 24px;">
        You can sign in at any time to review or update details, manage payments, and download your attendant card when eligible.
    </p>

    <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border-collapse:collapse;">
        <tr>
            <td style="border-radius:10px;background-color:#059669;">
                <a href="{{ rtrim(config('app.url'), '/') }}/dashboard" style="display:inline-block;padding:14px 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;">
                    Go to your dashboard
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:0;font-size:13px;color:#64748b;">
        Or copy this link: <a href="{{ rtrim(config('app.url'), '/') }}/dashboard" style="color:#047857;font-weight:500;">{{ rtrim(config('app.url'), '/') }}/dashboard</a>
    </p>

    <p style="margin:28px 0 0;font-size:15px;color:#334155;">
        Kind regards,<br>
        <strong style="color:#065f46;">The National Executive team</strong>
    </p>
@endsection
