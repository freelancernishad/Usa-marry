<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - Usa Marry</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Nunito Sans', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f5f7;
        }

        .header {
            border-bottom: 1px solid #801b1b;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            border: .5px solid #e6796d70;
            padding: 20px;
            box-sizing: border-box;
        }

        .button {
            display: inline-block;
            padding: 14px 28px;
            background-color: #c05346;
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            text-align: center;
            transition: transform 0.2s;
        }

        .button:hover {
            transform: scale(1.05);
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer .contact-info a {
            color: #c05346;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td style="background-color: #f4f5f7;">
                <div class="container">

                    <!-- Header -->
                    <div class="header">
                        <img src="https://usamarry.com/_next/image?url=%2Fusa-marry-logo.png&w=384&q=75" alt="Usa Marry Logo">
                    </div>

                    <!-- === BODY CONTENT START === -->
                    <div class="content" style="padding: 20px 0; text-align: center;">
                        <h1 style="font-size: 28px; font-weight: 800; color: #1a202c; margin: 0 0 10px 0;">
                            Hello {{ $user->name }},
                        </h1>
                        <p style="font-size: 16px; line-height: 1.6; color: #555; max-width: 500px; margin: 0 auto 30px auto;">
                            We received a request to reset your password. Click the button below to set a new password:
                        </p>

                        <!-- Reset Password Button -->
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="{{ $resetUrl }}" class="button">Reset Password</a>
                        </div>

                        <p style="font-size: 14px; line-height: 1.5; color: #555; max-width: 500px; margin: 0 auto 30px auto;">
                            If you did not request this change, please ignore this email.
                        </p>
                    </div>
                    <!-- === BODY CONTENT END === -->

                    <!-- Footer -->
                    <div class="footer">
                        <p><strong>Need help?</strong> Visit our <a href="https://usamarry.com/help">Help Center</a> or reach out to us directly.</p>
                        <div class="contact-info">
                            <div><strong>Email:</strong> <a href="mailto:contact@usamarry.com">contact@usamarry.com</a></div>
                            <div><strong>Phone:</strong> +1 (888) 887 5027</div>
                            <div><strong>Office:</strong> 74-09 37TH Avenue, Suite 203B, Jackson Heights, NY 11372</div>
                        </div>
                        <p><a href="https://usamarry.com">www.usamarry.com</a></p>
                        <p>&copy; {{ date('Y') }} Usa Marry. All rights reserved.</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
