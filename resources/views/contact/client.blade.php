<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('notifications.subject_client') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 10px 0;
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #0056b3;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
        .logo {
            text-align: center;
            margin: 20px 0;
        }
        .logo img{
            max-width: 100px !important;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header and Subject -->
        <div class="header">
            {{ __('notifications.subject_client') }}
        </div>

        <!-- Main Content -->
        <div class="content">
            <p>{{ __('notifications.greeting_client', ['name' => $data['name']]) }}</p>
            <p>{{ __('notifications.intro_client') }}</p>

            <div class="info">
                <h4>{{ __('notifications.contact_details') }}</h4>
                <p><strong>{{ __('notifications.name') }}:</strong> {{ $data['name'] }}</p>
                <p><strong>{{ __('notifications.email') }}:</strong> {{ $data['email'] }}</p>
            </div>

            <div class="info">
                <h4>{{ __('notifications.message') }}</h4>
                <p>{{ $data['message'] }}</p>
            </div>

            <!-- New Paragraph for Client Follow-Up -->
            <p>{{ __('notifications.follow_up') }}</p>
        </div>

        <!-- Logo Section Above Footer -->
        <div class="logo">
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Website Logo">
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>{{ __('notifications.footer_client') }}</p>
        </div>
    </div>
</body>
</html>