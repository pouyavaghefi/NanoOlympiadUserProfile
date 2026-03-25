<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activate Your Account</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #2d89ef;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Hello {{ $name }},</h2>
    <p>Thank you for registering. To activate your account, please click the button below:</p>
    <a class="btn" href="{{ url('/activate-account?token=' . $token) }}" style="color:white">
        Activate My Account
    </a>
    <p>If the button doesn't work, you can also copy and paste the following URL into your browser:</p>
    <p><a href="{{ url('/activate-account?token=' . $token) }}">{{ url('/activate-account?token=' . $token) }}</a></p>

    <div class="footer">
        <p>© {{ date('Y') }} Your Company. All rights reserved.</p>
    </div>
</div>
</body>
</html>
