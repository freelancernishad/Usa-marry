<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscription Invoice - UsaMarry</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fdf2f8;
            margin: 0;
            padding: 30px;
        }
        .invoice-box {
            background: #ffffff;
            max-width: 720px;
            margin: auto;
            padding: 40px 50px;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.08);
            border: 1px solid #f3dce4;
        }
        h1 {
            color: #be123c;
            text-align: center;
            margin-bottom: 28px;
            font-size: 30px;
            font-weight: 600;
        }
        p {
            font-size: 16px;
            color: #374151;
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        th, td {
            padding: 14px 18px;
            text-align: left;
            border: 1px solid #f3dce4;
            font-size: 15px;
        }
        th {
            background-color: #fef2f2;
            color: #b91c1c;
            font-weight: 500;
        }
        .total {
            font-weight: 700;
            background-color: #ffe4e6;
            color: #9f1239;
        }
        .footer {
            margin-top: 35px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #f3dce4;
            padding-top: 18px;
        }
        .btn {
            display: inline-block;
            background-color: #f43f5e;
            color: white;
            padding: 12px 30px;
            margin-top: 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #e11d48;
        }
        ul {
            margin: 0;
            padding-left: 22px;
            color: #374151;
        }
        ul li {
            margin-bottom: 6px;
        }
        .highlight {
            font-weight: 600;
            color: #111827;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h1>Subscription Invoice</h1>

        <p>Hello <span class="highlight">{{ $user->name ?? 'User' }}</span>,</p>
        <p>Thank you for purchasing the <strong>{{ $planName }}</strong> plan. Here’s a summary of your subscription:</p>

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
            <tr class="total">
                <th>Final Amount</th>
                <td>৳{{ number_format($subscription->final_amount, 2) }}</td>
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

        @if (is_array($subscription->formatted_plan_features) && count($subscription->formatted_plan_features))
            <h3 style="margin-top: 30px; color: #9f1239;">Included Features</h3>
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
            &copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. All rights reserved.
        </div>
    </div>
</body>
</html>
