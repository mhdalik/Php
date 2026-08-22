<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our CRM</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f4f5f7;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e4e8;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .content h2 {
            color: #1f2937;
            font-size: 20px;
            margin-top: 0;
        }
        .info-card {
            background-color: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .info-item {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-weight: 600;
            color: #4b5563;
            width: 80px;
            display: inline-block;
        }
        .info-value {
            color: #111827;
        }
        .footer {
            background-color: #f9fafb;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
    <!-- responsive container -->
    <div class="container">
        <!-- header section -->
        <div class="header">
            <h1>Welcome to Our CRM</h1>
        </div>
        <!-- content section -->
        <div class="content">
            <h2>Hello <?= esc($name) ?>,</h2>
            <p>We are thrilled to welcome you to our platform. Your customer account has been successfully created, and you now have access to our premium client services.</p>
            
            <div class="info-card">
                <div class="info-item">
                    <span class="info-label">Name:</span>
                    <span class="info-value"><?= esc($name) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Email:</span>
                    <span class="info-value"><?= esc($email) ?></span>
                </div>
            </div>

            <p>If you have any questions or need assistance setting up your profile, please feel free to reply directly to this email. Our support team is always here to help.</p>
            <p>Best regards,<br><strong>The CRM Team</strong></p>
        </div>
        <!-- footer section -->
        <div class="footer">
            &copy; <?= date('Y') ?> CRM System. All rights reserved.
        </div>
    </div>
</body>
</html>
