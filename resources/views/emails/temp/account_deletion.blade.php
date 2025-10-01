<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Account Deletion Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 30px;
        }
        .container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #eee;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        h2 {
            color: #e53935;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Account Deletion Request</h2>
        <p><span class="label">Name:</span> {{ $data['name'] }}</p>
        <p><span class="label">Email:</span> {{ $data['email'] }}</p>
        <p><span class="label">Reason:</span></p>
        <p>{{ $data['reason'] }}</p>
    </div>
</body>
</html>
