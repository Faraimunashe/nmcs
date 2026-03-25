@extends('emails.layout')

@section('preheader')
    Your conference fees are now fully paid for {{ trim($student->firstnames.' '.$student->surname) }}.
@endsection

@section('content')
    <p style="margin:0 0 20px;font-size:16px;color:#334155;">
        Hello <strong style="color:#0f172a;">{{ $student->user->name ?? 'there' }}</strong>,
    </p>
    <p style="margin:0 0 20px;">
        Great news — your <strong style="color:#065f46;">approved payments</strong> for
        <strong>{{ trim($student->firstnames.' '.$student->surname) }}</strong> now meet the active conference fee requirement for the NMCS Easter Conference 2026.
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #d1fae5;">
        <tr>
            <td style="padding:16px 20px;background-color:#f0fdf4;border-bottom:1px solid #d1fae5;">
                <p style="margin:0;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#047857;">Summary</p>
            </td>
        </tr>
        <tr>
            <td style="padding:18px 20px;background-color:#ffffff;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                    <tr>
                        <td style="padding:8px 0;font-size:15px;color:#64748b;">Conference fee</td>
                        <td align="right" style="padding:8px 0;font-size:15px;font-weight:600;color:#0f172a;">{{ $conferenceFeeAmount }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 0;font-size:15px;color:#64748b;">Total approved</td>
                        <td align="right" style="padding:8px 0;font-size:15px;font-weight:600;color:#059669;">{{ $totalPaid }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <p style="margin:0 0 24px;">
        Thank you for completing your payment. We look forward to welcoming you at the conference.
    </p>
    <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border-collapse:collapse;">
        <tr>
            <td style="border-radius:10px;background-color:#059669;">
                <a href="{{ rtrim(config('app.url'), '/') }}/dashboard" style="display:inline-block;padding:14px 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;">
                    View your dashboard
                </a>
            </td>
        </tr>
    </table>
    <p style="margin:0;font-size:13px;color:#64748b;">
        <a href="{{ rtrim(config('app.url'), '/') }}/payments" style="color:#047857;font-weight:500;">Payment history</a>
        &nbsp;·&nbsp;
        <a href="{{ rtrim(config('app.url'), '/') }}/dashboard" style="color:#047857;font-weight:500;">Dashboard</a>
    </p>
    <p style="margin:28px 0 0;font-size:15px;color:#334155;">
        Kind regards,<br>
        <strong style="color:#065f46;">The NMCS Zimbabwe team</strong>
    </p>
@endsection
