<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to NanoLympiad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }
        .cta {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .cta:hover {
            background-color: #004999;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Hello {{ $name }},</h2>

    <p>Welcome to <strong>NanoLympiad</strong>! Your registration is complete. Here are your login credentials:</p>

    <ul>
        <li><strong>Email:</strong> {{ $email }}</li>
        <li><strong>Password:</strong> {{ $password }}</li>
    </ul>

    <p>You can now access your profile using the button below:</p>

    <a href="{{ $loginUrl }}" class="cta">Go to My Profile</a>

    <p style="margin-top: 30px;">Best regards,<br>
        The NanoLympiad Team</p>
</div>
</body>
</html>
