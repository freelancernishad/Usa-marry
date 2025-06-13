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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #fce7f3;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            color: #be123c;
            margin: 0;
        }

        .header .invoice-meta {
            text-align: right;
            font-size: 14px;
            color: #6b7280;
        }

        .section-title {
            font-size: 18px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #9f1239;
            border-bottom: 1px solid #f3dce4;
            padding-bottom: 5px;
        }

        .info-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-box {
            width: 48%;
        }

        .info-box p {
            margin: 4px 0;
            font-size: 15px;
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
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h1>Invoice</h1>
            <div class="invoice-meta">
                <strong>Date:</strong> {{ now()->format('F d, Y') }}<br>
                <strong>Invoice ID:</strong> {{ $subscription->transaction_id }}
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h3 class="section-title">Billed To</h3>
                <p><strong>{{ $user->name ?? 'User' }}</strong></p>
                @if (!empty($user->email)) <p>{{ $user->email }}</p> @endif
                @if (!empty($user->phone)) <p>{{ $user->phone }}</p> @endif
            </div>
            <div class="info-box">
                <h3 class="section-title">Plan Info</h3>
                <p><strong>Plan:</strong> {{ $planName }}</p>
                <p><strong>Status:</strong> {{ ucfirst($subscription->status) }}</p>
                <p><strong>Duration:</strong> 
                    {{ \Carbon\Carbon::parse($subscription->start_date)->format('F j, Y') }} to 
                    {{ \Carbon\Carbon::parse($subscription->end_date)->format('F j, Y') }}
                </p>
            </div>
        </div>

        <h3 class="section-title">Payment Summary</h3>
        <table>
            <tr>
                <th>Description</th>
                <th>Amount (৳)</th>
            </tr>
            <tr>
                <td>Original Amount</td>
                <td>{{ number_format($subscription->original_amount, 2) }}</td>
            </tr>
            @if (!empty($subscription->discount_amount) && $subscription->discount_amount > 0)
                <tr>
                    <td>Discount ({{ $subscription->discount_percent }}%)</td>
                    <td>-{{ number_format($subscription->discount_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Coupon Code</td>
                    <td>{{ $subscription->coupon_code }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>Total Paid</td>
                <td>৳{{ number_format($subscription->final_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Payment Method</td>
                <td>{{ ucfirst($subscription->payment_method) }}</td>
            </tr>
        </table>

        @if (is_array($subscription->formatted_plan_features) && count($subscription->formatted_plan_features))
            <h3 class="section-title">Included Features</h3>
            <ul>
                @foreach ($subscription->formatted_plan_features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        @endif

        <div style="text-align: center;">
            <a href="{{ url('/') }}" class="btn">Back to Dashboard</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
