<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usa Marry - OTP Email</title>
    <style>
        /* Font */
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Nunito Sans', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f5f7; /* Softer background */
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            border: .5px solid #e6796d70; /* Border around the entire container */
            padding: 20px;
            box-sizing: border-box;
        }

        /* --- Header Styles (Kept similar) --- */
        .header {


            text-align: center;
            color: #333;
            padding: 10px 0 20px 0;
			border-bottom: 1px solid #801b1b
        }
        .header img {
            width: 180px;
            filter: contrast(150%);
        }
        .header .date {
            font-size: 14px;
            color: #777;
        }

        /* --- Body Styles (MODIFIED) --- */
        .content {
            padding: 20px 10px;
            text-align: center; /* Center align content for focus */
        }
        .content h1 {
            font-size: 26px;
            font-weight: 800;
            color: #1a202c;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
            margin: 0 0 10px 0;
        }

        /* OTP Display */
        .otp-container {
            margin: 30px auto;
            padding: 20px;
            background-color: #fef2f2; /* Very light tint of brand color */
            border: 2px dashed #c05346;
            border-radius: 8px;
            display: inline-block;
        }
        .otp-code {
            font-size: 36px;
            font-weight: 800;
            color: #c05346;
            letter-spacing: 8px; /* Spacing out the numbers */
            margin: 0;
        }

        .security-note {
            font-size: 14px !important;
            color: #999 !important;
            margin-top: 20px !important;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        /* --- Footer Styles (Kept similar) --- */
        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
        }
        .footer a {
            color: #c05346;
            text-decoration: none;
        }
        .footer p {
            margin: 5px 0;
        }

        /* Mobile responsive */
        @media (max-width: 600px) {
            .otp-code {
                font-size: 30px;
                letter-spacing: 5px;
            }
            .content h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <img src="https://usamarry.com/_next/image?url=%2Fusa-marry-logo.png&w=384&q=75" alt="Usa Marry Logo">
        </div>

        <!-- === BODY CONTENT START (MODIFIED) === -->
        <div class="content">
            <h1>Verify Your Account</h1>
            {{-- <p>Hi [User Name],</p> --}}
            <p>Please use the following One-Time Password (OTP) to complete your action. This code is sensitive and should not be shared.</p>

            <!-- OTP Display -->
            <div class="otp-container">
                <p class="otp-code">{{ $otp }}</p>
            </div>

            <p>This code is valid for the next <strong>10 minutes</strong>.</p>

            <p class="security-note">If you did not request this verification code, you can safely ignore this email. Someone else might have typed your email address by mistake.</p>
        </div>
        <!-- === BODY CONTENT END === -->

         <!-- Footer (Unchanged) -->
         <div class="footer">
            <p><strong>Need help?</strong> Visit our <a href="https://usamarry.com/help" style="color: #c05346;">Help Center</a> or reach out to us directly.</p>
            <div class="contact-info">
                <div><strong>Email:</strong> <a href="mailto:contact@usamarry.com">contact@usamarry.com</a></div>
                <div><strong>Phone:</strong> +1 (888) 887 5027</div>
                <div><strong>Office:</strong> 74-09 37TH Avenue, Suite 203B, Jackson Heights, NY 11372</div>
            </div>
            <p><a href="https://usamarry.com" style="color: #c05346;">www.usamarry.com</a></p>
        </div>
    </div>
</body>
</html>
