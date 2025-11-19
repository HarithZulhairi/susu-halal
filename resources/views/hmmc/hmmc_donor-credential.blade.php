<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🎉 Welcome to HMMC - Your Donor Journey Begins!</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #FF6B6B 0%, #FFD93D 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header::before {
            content: "🎉";
            font-size: 60px;
            position: absolute;
            top: 20px;
            right: 30px;
            opacity: 0.8;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-message {
            font-size: 18px;
            color: #2d3748;
            margin-bottom: 30px;
        }
        .credentials-card {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin: 25px 0;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .login-button {
            display: inline-block;
            background: linear-gradient(135deg, #FF6B6B 0%, #FFD93D 100%);
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 30px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
            transition: transform 0.3s ease;
        }
        .login-button:hover {
            transform: translateY(-2px);
        }
        .security-note {
            background: #fff9e6;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #FFD93D;
            margin: 25px 0;
        }
        .heart-icon {
            color: #FF6B6B;
            font-size: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e2e8f0;
            color: #666;
        }
        .celebrate-text {
            font-size: 24px;
            font-weight: bold;
            color: #FF6B6B;
            margin: 20px 0;
        }
        .credential-box {
            background: rgba(11, 80, 160, 0.774);
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">🌟 Welcome to HMMC Family! 🌟</h1>
            <p style="font-size: 18px; margin: 10px 0 0 0; opacity: 0.9;">Your Generous Journey Begins Here!</p>
        </div>
        
        <div class="content">
            <div class="welcome-message">
                <p>Dear <strong style="color: #FF6B6B;">{{ $fullname }}</strong>,</p>
                
                <p>We are absolutely <strong>THRILLED</strong> to welcome you to the HALIMATUSSAADIA Mother's Milk Centre family! 🎊</p>
                
                <p>Your decision to become a milk donor is truly inspiring and will make a <span class="heart-icon">❤️</span> profound difference <span class="heart-icon">❤️</span> in the lives of precious babies and their families.</p>
            </div>

            <div class="celebrate-text">
                🎉 Your Account is Ready! 🎉
            </div>

            <div class="credentials-card">
                <h3 style="margin-top: 0; color: white; font-size: 20px;">Your Special Access Details:</h3>
                
                <div class="credential-box">
                    <strong>👤 Username:</strong><br>
                    your NRIC
                </div>
                
                <div class="credential-box">
                    <strong>🔑 Temporary Password:</strong><br>
                    {{ $password }}
                </div>
            </div>

            <div style="text-align: center;">
                <p style="font-size: 16px; color: #4a5568; margin-bottom: 20px;">
                    Ready to start your amazing donor journey?
                </p>
                <a href="{{ $loginUrl }}" class="login-button">
                    🚀 Launch My Donor Portal 🚀
                </a>
            </div>

            <div class="security-note">
                <strong>🔒 Important Security Tips:</strong>
                <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                    <li>Change your password after first login</li>
                    <li>Keep your credentials confidential</li>
                    <li>Your NRIC username ensures secure access</li>
                </ul>
            </div>

            <div style="background: #e8f5e8; padding: 20px; border-radius: 10px; text-align: center; margin: 25px 0;">
                <p style="margin: 0; color: #2d5016; font-size: 16px;">
                    <strong>🌈 You're About to Change Lives! 🌈</strong><br>
                    Every drop of milk you donate brings hope and health to families in need.
                </p>
            </div>

            <p>If you need any help or have questions, our friendly support team is always here for you!</p>
            
            <p>With heartfelt gratitude,</p>
        </div>
        
        <div class="footer">
            <p style="margin: 0; font-size: 16px;">
                <strong>HMMC Team</strong><br>
                <span style="color: #FF6B6B;">HALIMATUSSAADIA Mother's Milk Centre</span>
            </p>
            
            <p style="font-size: 12px; color: #999; margin: 15px 0 0 0;">
                💫 Together, we're nourishing futures, one drop at a time 💫<br>
                <em>This is an automated message. Please do not reply to this email.</em>
            </p>
        </div>
    </div>
</body>
</html>