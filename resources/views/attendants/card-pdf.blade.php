<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendant Card</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 24px; color: #0f172a; }
        .header {
            width: 100%;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 10px;
            margin-bottom: 16px;
        }
        .header-table {
            width: 100%;
        }
        .logo-cell {
            width: 80px;
        }
        .logo {
            max-height: 40px;
        }
        .inst-logo {
            max-height: 32px;
        }
        .title-cell {
            text-align: left;
        }
        .inst-logo-cell {
            width: 80px;
            text-align: right;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            color: #0f766e;
        }
        .subtitle {
            font-size: 11px;
            color: #64748b;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 6px;
            color: #0f172a;
        }
        table.details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.details td.label {
            width: 25%;
            padding: 4px 6px;
            font-size: 10px;
            color: #64748b;
        }
        table.details td.value {
            padding: 4px 6px;
            font-size: 11px;
            font-weight: bold;
        }
        .payment-box {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px 10px;
            margin-top: 4px;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .payment-label {
            font-size: 10px;
            color: #64748b;
        }
        .payment-value {
            font-size: 11px;
            font-weight: bold;
        }
        .payment-balance {
            color: #b91c1c;
        }
        .qr-container {
            margin-top: 20px;
            text-align: right;
        }
        .qr-label {
            font-size: 10px;
            color: #64748b;
            margin-bottom: 4px;
        }
        .qr img {
            width: 120px;
            height: 120px;
        }
        .footer {
            margin-top: 16px;
            font-size: 9px;
            color: #94a3b8;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if(!empty($logoBase64))
                        <img class="logo" src="data:image/jpeg;base64,{{ $logoBase64 }}" alt="NMCS Logo">
                    @endif
                </td>
                <td class="title-cell">
                    <div class="title">NMCS Conference Attendant Card</div>
                    <div class="subtitle">{{ $fullName }}</div>
                </td>
                <td class="inst-logo-cell">
                    @if(!empty($institutionLogoBase64))
                        <img class="inst-logo" src="data:image/jpeg;base64,{{ $institutionLogoBase64 }}" alt="Institution Logo">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Attendant Information</div>
    <table class="details">
        <tr>
            <td class="label">Full Name</td>
            <td class="value">{{ $fullName }}</td>
        </tr>
        <tr>
            <td class="label">Gender</td>
            <td class="value">{{ $gender }}</td>
        </tr>
        <tr>
            <td class="label">National ID</td>
            <td class="value">{{ $nationalId ?: 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Region</td>
            <td class="value">{{ $region ?: 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Institution</td>
            <td class="value">{{ $institution ?: 'N/A' }}</td>
        </tr>
    </table>

    <div class="section-title">Payment Summary</div>
    <div class="payment-box">
        <div class="payment-row">
            <span class="payment-label">Conference Fee</span>
            <span class="payment-value">${{ number_format($conferenceFee, 2) }}</span>
        </div>
        <div class="payment-row">
            <span class="payment-label">Outstanding Balance</span>
            <span class="payment-value payment-balance">${{ number_format($balance, 2) }}</span>
        </div>
    </div>

    <div class="qr-container">
        <div class="qr-label">Scan to verify this attendant (admin only)</div>
        <div class="qr">
            @if(!empty($qrImageBase64))
                <img src="data:image/svg+xml;base64,{{ $qrImageBase64 }}" alt="Verification QR Code">
            @endif
        </div>
    </div>

    <div class="footer">
        Generated on {{ now()->format('d M Y H:i') }} · Powered by Fariwe
    </div>
</body>
</html>

