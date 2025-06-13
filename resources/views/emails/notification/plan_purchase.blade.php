<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Plan Purchase Invoice</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 30px;
        }
        .invoice-box {
            background: #ffffff;
            max-width: 700px;
            margin: auto;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            border: 1px solid #e5e7eb;
        }
        h1 {
            color: #1f2937;
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
        }
        p {
            font-size: 16px;
            color: #374151;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #d1d5db;
            font-size: 15px;
        }
        th {
            background-color: #f9fafb;
            color: #111827;
            font-weight: 600;
        }
        .total {
            font-weight: 700;
            background-color: #e0f2fe;
            color: #0284c7;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
        }
        .btn {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            margin-top: 25px;
            border-radius: 6px;
            text-decoration: none;
            text-align: center;
        }
        .btn:hover {
            background-color: #2563eb;
        }
        ul {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h1>Subscription Invoice</h1>

        <p>Hi {{ $user->name ?? 'User' }},</p>
        <p>Thank you for purchasing the <strong>{{ $planName }}</strong> plan. Here are your subscription details:</p>

        <table>
            <tr>
                <th>Plan Name</th>
                <td>{{ $planName }}</td>
            </tr>
            <tr>
                <th>Original Amount</th>
                <td>৳{{ number_format($subscription->original_amount, 2) }}</td>
            </tr>
            @if (!empty($subscription->discount_amount) && $subscription->discount_amount > 0)
                <tr>
                    <th>Discount</th>
                    <td>৳{{ number_format($subscription->discount_amount, 2) }} ({{ $subscription->discount_percent }}%)</td>
                </tr>
                <tr>
                    <th>Coupon Code</th>
                    <td>{{ $subscription->coupon_code }}</td>
                </tr>
            @endif
            <tr>
                <th>Final Amount</th>
                <td><strong>৳{{ number_format($subscription->final_amount, 2) }}</strong></td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>{{ ucfirst($subscription->payment_method) }}</td>
            </tr>
            <tr>
                <th>Transaction ID</th>
                <td>{{ $subscription->transaction_id }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ ucfirst($subscription->status) }}</td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('F j, Y') }}</td>
            </tr>
            <tr>
                <th>End Date</th>
                <td>{{ \Carbon\Carbon::parse($subscription->end_date)->format('F j, Y') }}</td>
            </tr>
        </table>
{{-- 
        @if (is_array($subscription->plan_features) && count($subscription->plan_features))
            <h3 style="margin-top: 30px;">Included Features:</h3>
            <ul>
                @foreach ($subscription->plan_features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        @endif --}}

        <div style="text-align: center;">
            <a href="{{ url('/') }}" class="btn">Go to Dashboard</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
