@extends('emails.layout')

@section('preheader')
    Your payment of {{ number_format((float) $payment->final_amount, 2) }} has been approved.
@endsection

@section('content')
    <p style="margin:0 0 8px;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#059669;">
        Payment approved
    </p>
    <p style="margin:0 0 20px;font-size:16px;color:#334155;">
        Hello <strong style="color:#0f172a;">{{ $payment->student->user->name ?? 'there' }}</strong>,
    </p>
    <p style="margin:0 0 20px;">
        Your payment for <strong>{{ trim($payment->student->firstnames.' '.$payment->student->surname) }}</strong> has been
        <strong style="color:#059669;">approved</strong>. Thank you for supporting the NMCS Zimbabwe Easter Conference.
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #d1fae5;">
        <tr>
            <td style="padding:16px 20px;background-color:#ecfdf5;border-bottom:1px solid #a7f3d0;">
                <p style="margin:0;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#047857;">Payment details</p>
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
                    @if($payment->paymentMethod)
                        <tr>
                            <td style="padding:6px 0;font-size:14px;color:#64748b;">Method</td>
                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->paymentMethod->name }}</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border-collapse:collapse;">
        <tr>
            <td style="border-radius:10px;background-color:#059669;">
                <a href="{{ rtrim(config('app.url'), '/') }}/payments" style="display:inline-block;padding:14px 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;">
                    View payment history
                </a>
            </td>
        </tr>
    </table>
    <p style="margin:0;font-size:13px;color:#64748b;">
        <a href="{{ rtrim(config('app.url'), '/') }}/payments" style="color:#047857;font-weight:500;">{{ rtrim(config('app.url'), '/') }}/payments</a>
    </p>
    <p style="margin:28px 0 0;font-size:15px;color:#334155;">
        Thank you,<br>
        <strong style="color:#065f46;">The NMCS Zimbabwe team</strong>
    </p>
@endsection
