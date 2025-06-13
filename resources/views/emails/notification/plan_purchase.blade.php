<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fdf2f8;
            margin: 0;
            padding: 40px;
            color: #374151;
        }

        .invoice-box {
            max-width: 850px;
            margin: auto;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: 1px solid #f3dce4;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #9f1239;
            border-bottom: 1px solid #f3dce4;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 30px;
        }

        th, td {
            padding: 12px 16px;
            border: 1px solid #f3dce4;
            font-size: 15px;
            text-align: left;
        }

        th {
            background-color: #fef2f2;
            font-weight: 500;
            color: #b91c1c;
        }

        .total-row {
            background-color: #ffe4e6;
            font-weight: 600;
            color: #9f1239;
        }

        ul {
            margin: 10px 0 30px;
            padding-left: 20px;
        }

        ul li {
            margin-bottom: 6px;
            font-size: 15px;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #f3dce4;
            padding-top: 20px;
        }

        .btn {
            display: inline-block;
            background-color: #f43f5e;
            color: #fff;
            padding: 12px 30px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            margin-top: 10px;
        }

        .btn:hover {
            background-color: #e11d48;
        }

        .info-table td {
            border: none;
            padding: 4px 0;
        }
    </style>
</head>
<body>
    <div class="invoice-box">

        <!-- HEADER using TABLE -->
        <table style="width: 100%; border-bottom: 2px solid #fce7f3; margin-bottom: 30px;">
            <tr>
                <td style="vertical-align: middle;">
                    <img src="https://usamarry.com/_next/image?url=%2Fusa-marry-logo.png&w=384&q=75" alt="{{ config('app.name') }} Logo" style="height: 40px; vertical-align: middle; margin-right: 10px;">
                    <span style="font-size: 20px; font-weight: 600; color: #be123c;"></span>
                </td>
                <td style="text-align: right; font-size: 14px; color: #6b7280;">
                    <strong>Date:</strong> {{ now()->format('F d, Y') }}<br>
                    <strong>Invoice ID:</strong> {{ $subscription->transaction_id }}
                </td>
            </tr>
        </table>

        <!-- Billed To & Plan Info -->
        <table class="info-table" style="width: 100%; margin-bottom: 30px;">
            <tr>
                <td style="width: 50%;">
                    <h3 class="section-title">Billed To</h3>
                    <p><strong>{{ $user->name ?? 'User' }}</strong></p>
                    @if (!empty($user->email)) <p>{{ $user->email }}</p> @endif
                    @if (!empty($user->phone)) <p>{{ $user->phone }}</p> @endif
                </td>
                <td style="width: 50%;">
                    <h3 class="section-title">Plan Info</h3>
                    <p><strong>Plan:</strong> {{ $planName }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($subscription->status) }}</p>
                    <p><strong>Duration:</strong>
                        {{ \Carbon\Carbon::parse($subscription->start_date)->format('F j, Y') }} to
                        {{ \Carbon\Carbon::parse($subscription->end_date)->format('F j, Y') }}
                    </p>
                </td>
            </tr>
        </table>

        <!-- Payment Summary -->
        <h3 class="section-title">Payment Summary</h3>
        <table>
            <tr>
                <th>Description</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>Original Amount</td>
                <td>{{ number_format($subscription->original_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td>-{{ number_format($subscription->discount_amount ?? 0, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Paid</td>
                <td>{{ number_format($subscription->final_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td>{{ ucfirst($subscription->payment_method) }}</td>
            </tr>
        </table>

        <!-- Included Features -->
        @if (is_array($subscription->formatted_plan_features) && count($subscription->formatted_plan_features))
            <h3 class="section-title">Included Features</h3>
            <ul>
                @foreach ($subscription->formatted_plan_features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        @endif

        <!-- CTA -->
        <div style="text-align: center;">
            <a href="{{ url('/') }}" class="btn btn-danger">Back to Dashboard</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>

    </div>
</body>
</html>
