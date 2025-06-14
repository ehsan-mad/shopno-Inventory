<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - Shopno Inventory</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7fa;
            padding: 20px;
            line-height: 1.6;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
            padding: 40px 20px;
        }
        
        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.5;
        }
        
        .otp-container {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 12px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: 0 4px 20px rgba(240, 147, 251, 0.3);
        }
        
        .otp-label {
            font-size: 14px;
            color: white;
            opacity: 0.9;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: white;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        
        .expiry-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
            font-size: 14px;
        }
        
        .instructions {
            font-size: 14px;
            color: #666;
            margin: 20px 0;
            text-align: left;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }
        
        .instructions ul {
            margin-left: 20px;
        }
        
        .instructions li {
            margin-bottom: 8px;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer p {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }
        
        .company-name {
            font-weight: 600;
            color: #667eea;
        }
        
        .security-notice {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #0c5460;
            font-size: 13px;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .otp-code {
                font-size: 28px;
                letter-spacing: 4px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>🔐 OTP Verification</h1>
            <p>Shopno Inventory System</p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello there! 👋
            </div>
            
            <div class="message">
                We received a request to verify your account. Please use the OTP code below to complete your verification.
            </div>
            
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ $otp }}</div>
            </div>
            
            <div class="expiry-notice">
                ⏰ <strong>Important:</strong> This OTP will expire in 10 minutes for security reasons.
            </div>
            
            <div class="instructions">
                <strong>How to use this code:</strong>
                <ul>
                    <li>Copy the 4-digit code above</li>
                    <li>Return to the verification page</li>
                    <li>Enter the code in the OTP field</li>
                    <li>Click "Verify" to complete the process</li>
                </ul>
            </div>
            
            <div class="security-notice">
                🛡️ <strong>Security Notice:</strong> If you didn't request this verification, please ignore this email. Never share your OTP with anyone.
            </div>
        </div>
        
        <div class="footer">
            <p><span class="company-name">Shopno Inventory</span></p>
            <p>Secure • Reliable • Professional</p>
            <p>© {{ date('Y') }} Shopno Inventory. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
