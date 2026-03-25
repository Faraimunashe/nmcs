@extends('emails.layout')

@section('preheader')
    A new payment is awaiting your review in the admin area.
@endsection

@section('content')
    <p style="margin:0 0 8px;font-size:12px;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#047857;">
        Action required
    </p>
    <p style="margin:0 0 20px;font-size:16px;color:#334155;">
        Hello,
    </p>
    <p style="margin:0 0 20px;">
        A participant has recorded a <strong style="color:#065f46;">pending payment</strong> that needs to be approved or rejected in the NMCS Zimbabwe admin system.
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 24px;border-collapse:collapse;border-radius:12px;overflow:hidden;border:1px solid #d1fae5;">
        <tr>
            <td style="padding:16px 20px;background-color:#ecfdf5;border-bottom:1px solid #a7f3d0;">
                <p style="margin:0;font-size:12px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#047857;">Participant &amp; payment</p>
            </td>
        </tr>
        <tr>
            <td style="padding:18px 20px;background-color:#ffffff;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
                    <tr>
                        <td style="padding:6px 0;font-size:14px;color:#64748b;">Student</td>
                        <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#0f172a;">{{ trim($payment->student->firstnames.' '.$payment->student->surname) }}</td>
                    </tr>
                    @if($payment->student->user)
                        <tr>
                            <td style="padding:6px 0;font-size:14px;color:#64748b;">Account email</td>
                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->student->user->email }}</td>
                        </tr>
                    @endif
                    @if($payment->student->institution)
                        <tr>
                            <td style="padding:6px 0;font-size:14px;color:#64748b;">Institution</td>
                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->student->institution->name }}@if($payment->student->institution->code) <span style="font-weight:500;color:#64748b;">({{ $payment->student->institution->code }})</span>@endif</td>
                        </tr>
                    @endif
                    @if($payment->student->institution?->region)
                        <tr>
                            <td style="padding:6px 0;font-size:14px;color:#64748b;">Region</td>
                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->student->institution->region->name }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td style="padding:6px 0;font-size:14px;color:#64748b;vertical-align:top;">Phone(s)</td>
                        <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->student->phones->isNotEmpty() ? $payment->student->phones->pluck('phone')->join(', ') : '—' }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:14px 0 8px;border-top:1px solid #e5e7eb;">
                            <p style="margin:0;font-size:11px;font-weight:600;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">Payment</p>
                        </td>
                    </tr>
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
                    @if($payment->paymentRecipient)
                        <tr>
                            <td style="padding:6px 0;font-size:14px;color:#64748b;">Payment recipient</td>
                            <td align="right" style="padding:6px 0;font-size:14px;font-weight:600;color:#334155;">{{ $payment->paymentRecipient->name }}</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
    <table role="presentation" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border-collapse:collapse;">
        <tr>
            <td style="border-radius:10px;background-color:#059669;">
                <a href="{{ route('admin.payments.show', $payment->id) }}" style="display:inline-block;padding:14px 28px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:15px;font-weight:600;color:#ffffff;text-decoration:none;">
                    Review in admin
                </a>
            </td>
        </tr>
    </table>
    <p style="margin:0;font-size:13px;color:#64748b;">
        You can also open the <a href="{{ route('admin.payments.index') }}" style="color:#047857;font-weight:500;">payments list</a> to see all pending items.
    </p>
    <p style="margin:28px 0 0;font-size:15px;color:#334155;">
        Kind regards,<br>
        <strong style="color:#065f46;">NMCS Zimbabwe system</strong>
    </p>
@endsection
