<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .label {
            font-weight: bold;
            color: #495057;
            display: block;
            margin-bottom: 5px;
        }
        .value {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }
        .message-content {
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
        <p>You have received a new message through the contact form on your website.</p>
    </div>

    <div class="content">
        <div class="field">
            <span class="label">Name:</span>
            <div class="value">{{ $data['name'] }}</div>
        </div>

        <div class="field">
            <span class="label">Email:</span>
            <div class="value">{{ $data['email'] }}</div>
        </div>

        <div class="field">
            <span class="label">Message:</span>
            <div class="value message-content">{{ $data['message'] }}</div>
        </div>

        <div class="field">
            <span class="label">Submitted at:</span>
            <div class="value">{{ now()->format('F j, Y \a\t g:i A') }}</div>
        </div>
    </div>

    <hr style="margin: 30px 0; border: 1px solid #e9ecef;">

    <p style="font-size: 14px; color: #6c757d;">
        This email was automatically generated from your website's contact form.
        Please reply directly to {{ $data['email'] }} to respond to this inquiry.
    </p>
</body>
</html>
