<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Tax Invoice - {{ $invoice->invoice_number }}</title>

    <style>
        @page {
            margin: 10mm;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1e293b;
        }

        .container {
            padding: 30px;
        }

        /* Brand */
        .primary {
            color: #2563eb;
        }

        .muted {
            color: #64748b;
        }

        /* Header */
        .header {
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .flex {
            width: 100%;
        }

        .left {
            float: left;
        }

        .right {
            float: right;
            text-align: right;
        }

        .clearfix {
            clear: both;
        }

        .brand-title {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }

        .invoice-title {
            font-size: 20px;
            color: #cbd5f5;
            margin: 0;
        }

        /* Card */
        .card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info td {
            width: 50%;
            vertical-align: top;
            padding: 10px 0;
        }

        .items th {
            background: #2563eb;
            color: #fff;
            padding: 10px;
            font-size: 11px;
            text-align: left;
        }

        .items td {
            padding: 12px 10px;
            border-bottom: 1px solid #e2e8f0;
        }

        .text-right {
            text-align: right;
        }

        /* Totals */
        .totals {
            width: 40%;
            margin-left: 60%;
            margin-top: 20px;
        }

        .totals td {
            padding: 6px 0;
        }

        .grand {
            font-size: 14px;
            font-weight: bold;
            color: #2563eb;
            border-top: 2px solid #2563eb;
        }

        /* Amount Words */
        .amount-box {
            margin-top: 25px;
            padding: 12px;
            border-left: 4px solid #2563eb;
            background: #f1f5f9;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 40px;
            font-size: 9px;
            text-align: center;
            color: #94a3b8;
        }

        .items th {
            text-align: left;
        }

        .items td.text-right,
        .items th.text-right {
            text-align: right;
        }

        .items tbody tr:last-child td {
            border-bottom: none;
        }
    </style>
</head>

<body>
    <div class="container">

        <!-- HEADER -->
        <div class="header">
            <div class="left">
                <div class="brand-title primary">FileCollect</div>
                <div class="muted" style="font-size:10px;">Secure • Organize</div>
            </div>

            <div class="right">
                <div class="invoice-title">TAX INVOICE</div>
                <div class="primary" style="font-weight:bold;">
                    #{{ $invoice->invoice_number }}
                </div>
            </div>

            <div class="clearfix"></div>
        </div>

        <!-- BILLING -->
        <table class="info">
            <tr>
                <td>
                    <div class="muted"><strong>SOLD BY</strong></div>
                    <div style="font-size:13px;font-weight:bold;">
                        {{ $saas['name'] }}
                    </div>
                    {{ $saas['address'] }}<br>
                    GSTIN: {{ $saas['gstin'] }}<br>
                    {{ $saas['email'] }}
                </td>

                <td class="text-right">
                    <div class="muted"><strong>BILL TO</strong></div>
                    <div style="font-size:13px;font-weight:bold;">
                        {{ $userCompany->company_name ?? $invoice->user->name }}
                    </div>

                    @if ($userCompany)
                        {{ $userCompany->address_line_1 }}, {{ $userCompany->city }}<br>
                        {{ $userCompany->state }}, {{ $userCompany->country }}<br>
                        {{ $userCompany->phone }}
                    @else
                        {{ $invoice->user->email }}
                    @endif
                </td>
            </tr>
        </table>

        <!-- META -->
        <div class="card">
            <table>
                <tr>
                    <td><strong>Date:</strong> {{ $invoice->created_at->format('d M, Y') }}</td>
                    <td class="text-right"><strong>Payment:</strong> Razorpay</td>
                </tr>
            </table>
        </div>

        <!-- ITEMS -->
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 60%;">Description</th>
                    <th style="width: 20%;" class="text-right">Rate</th>
                    <th style="width: 20%;" class="text-right">Amount</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td style="vertical-align: top;">
                        <div style="font-weight: bold; margin-bottom: 4px;">
                            {{ $invoice->plan->name ?? 'Subscription Plan' }}
                        </div>

                        <div class="muted" style="font-size: 10px;">
                            Period: {{ $invoice->created_at->format('M Y') }}
                        </div>
                    </td>

                    <td class="text-right" style="vertical-align: middle;">
                        {{ number_format($subtotal, 2) }}
                    </td>

                    <td class="text-right" style="vertical-align: middle; font-weight: bold;">
                        {{ number_format($subtotal, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- TOTALS -->
        <table class="totals">
            <tr>
                <td>Subtotal</td>
                <td class="text-right">{{ number_format($subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>CGST (9%)</td>
                <td class="text-right">{{ number_format($cgst, 2) }}</td>
            </tr>
            <tr>
                <td>SGST (9%)</td>
                <td class="text-right">{{ number_format($sgst, 2) }}</td>
            </tr>
            <tr class="grand">
                <td>Total</td>
                <td class="text-right">
                    {{ $invoice->currency }} {{ number_format($total, 2) }}
                </td>
            </tr>
        </table>

        <!-- AMOUNT IN WORDS -->
        <div>
            <div class="muted" style="font-size:10px;text-transform:uppercase;">
                Amount in Words
            </div>

            <div class="amount-box">
                {{ $amountInWords }}
            </div>
        </div>

        <!-- TRANSACTION -->
        <div class="card" style="margin-top:25px;">
            <strong>Transaction Details</strong><br><br>

            <span class="muted">Payment ID:</span>
            {{ $invoice->razorpay_payment_id }}<br>

            <span class="muted">Paid On:</span>
            {{ $invoice->paid_at ? $invoice->paid_at->format('d M Y, h:i A') : $invoice->created_at->format('d M Y') }}
        </div>

        <!-- FOOTER -->
        <div class="footer">
            This is a system-generated invoice — no signature required.<br>
            <strong>FileCollect</strong> • Secure Client Document Collection
        </div>

    </div>
</body>

</html>
