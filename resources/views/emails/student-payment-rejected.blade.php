@extends('emails.layout')

@section('preheader')
    An update on your NMCS payment — please review the details below.
@endsection

@section('content')
    <p style="margin:0 0 8px;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#b45309;">
        Payment not approved
    </p>
    <p style="margin:0 0 20px;font-size:16px;color:#334155;">
        Hello <strong style="color:#0f172a;">{{ $payment->student->user->name ?? 'there' }}</strong>,
    </p>
    <p style="margin:0 0 20px;">
        Your payment for <strong>{{ trim($payment->student->firstnames.' '.$payment->student->surname) }}</strong> could not be approved at this time.
        Please review the information below and contact the National Executive team if you need assistance.
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 20px;border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #e5e7eb;">
        <tr>
            <td style="padding:16px 20px;background-color:#f8fafc;border-bottom:1px solid #e5e7eb;">
                <p style="margin:0;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#64748b;">Payment details</p>
            </td>
        </tr>
        <tr>
            <td style="padding:18px 20px;background-color:#ffffff;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 0;font-size:14px;color:#64748b;">Amount (after charges)</td>
                        <td align="right" style="padding:6px 0;font-size:15px;font-weight:600;color:#0f172a;">{{ number_format((float) $payment->final_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:6px 0;font-size:14px;color:#64748b;">Purpose</td>
                        <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->purpose instanceof \BackedEnum ? $payment->purpose->value : $payment->purpose }}</td>
                    </tr>
                    @if($payment->reference)
                        <tr>
                            <td style="padding:6px 0;font-size:14px;color:#64748b;">Reference</td>
                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->reference }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="padding:6px 0;font-size:14px;color:#64748b;">Payment date</td>
                        <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->payment_date->format('j F Y') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    @if($payment->rejection_reason)
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" bgcolor="#fffbeb" style="margin:0 0 24px;border-collapse:collapse;border-radius:12px;background-color:#fffbeb;border:1px solid #fde68a;border-left:4px solid #f59e0b;">
            <tr>
                <td style="padding:16px 20px;">
                    <p style="margin:0 0 6px;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#b45309;">Reason provided</p>
                    <p style="margin:0;font-size:15px;line-height:1.55;color:#78350f;">{{ $payment->rejection_reason }}</p>
                </td>
            </tr>
        </table>
    @endif
    <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border-collapse:collapse;">
        <tr>
            <td style="border-radius:10px;background-color:#059669;">
                <a href="{{ rtrim(config('app.url'), '/') }}/payments" style="display:inline-block;padding:14px 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;">
                    Review in your account
                </a>
            </td>
        </tr>
    </table>
    <p style="margin:0;font-size:13px;color:#64748b;">
        If you have questions, please contact the conference organisers using the details they have shared with participants.
    </p>
    <p style="margin:28px 0 0;font-size:15px;color:#334155;">
        Kind regards,<br>
        <strong style="color:#065f46;">The NMCS Zimbabwe team</strong>
    </p>
@endsection
