<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Usa Marry - Get Started</title>
    <style>
        /* Font */
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Nunito Sans', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f5f7;
        }

        .header {
            border-bottom: 1px solid #801b1b
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

        /* Footer Styles */
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
                    <div class="header"
                        style="text-align: center; color: #333; padding: 20px 0;">
                        <img src="https://usamarry.com/_next/image?url=%2Fusa-marry-logo.png&w=384&q=75"
                            alt="Usa Marry Logo" style="width: 180px; filter: contrast(150%);">

                    </div>

                    <!-- === BODY CONTENT START (MODIFIED) === -->
                    <div class="content" style="padding: 20px 0; text-align: center;">

                        <h1 style="font-size: 28px; font-weight: 800; color: #1a202c; margin: 0 0 10px 0;">Welcome to
                            Usa Marry, {{ $name }}</h1>
                        <p
                            style="font-size: 16px; line-height: 1.6; color: #555; max-width: 500px; margin: 0 auto 30px auto;">
                            We're thrilled to have you join our community. You've taken the first step towards finding
                            your perfect life partner.
                        </p>

                        <!-- Get Started Section -->
                        <div style="text-align: left; background-color: #f8f9fa; border-radius: 8px; padding: 25px;">
                            <h2 style="margin: 0 0 20px 0; font-size: 20px; text-align: center; color: #2d3748;">Your
                                Journey Starts Here</h2>
                            <!-- Step 1 -->
                            <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#28a745"
                                    style="min-width: 24px; margin-right: 15px; margin-top: 3px;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <div>
                                    <h3 style="margin: 0 0 2px 0; font-size: 16px; color: #2d3748;">Complete Your
                                        Profile</h3>
                                    <p style="margin: 0; font-size: 14px; color: #718096; line-height: 1.5;">A detailed
                                        profile (aim for 80%+) gets more views and better matches.</p>
                                </div>
                            </div>
                            <!-- Step 2 -->
                            <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#28a745"
                                    style="min-width: 24px; margin-right: 15px; margin-top: 3px;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <div>
                                    <h3 style="margin: 0 0 2px 0; font-size: 16px; color: #2d3748;">Add Your Best Photos
                                    </h3>
                                    <p style="margin: 0; font-size: 14px; color: #718096; line-height: 1.5;">First
                                        impressions count! Upload clear, recent photos to increase your chances.</p>
                                </div>
                            </div>
                            <!-- Step 3 -->
                            <div style="display: flex; align-items: flex-start;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#28a745"
                                    style="min-width: 24px; margin-right: 15px; margin-top: 3px;">
                                    <path
                                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                                </svg>
                                <div>
                                    <h3 style="margin: 0 0 2px 0; font-size: 16px; color: #2d3748;">Start Exploring</h3>
                                    <p style="margin: 0; font-size: 14px; color: #718096; line-height: 1.5;">Don't wait!
                                        Start browsing profiles and send connection requests.</p>
                                </div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="https://usamarry.com/dashboard" class="button">Complete Your Profile Now</a>
                        </div>

                        <!-- Security Section -->
                        <div style="text-align: left; border-top: 1px solid #e2e8f0; padding-top: 25px;">
                            <h2 style="margin: 0 0 20px 0; font-size: 20px; text-align: center; color: #2d3748;">Your
                                Safety is Our Priority</h2>
                            <p
                                style="text-align: center; font-size: 15px; line-height: 1.6; color: #555; max-width: 500px; margin: 0 auto 20px auto;">
                                We are dedicated to providing a secure platform. You are always in control.
                            </p>
                            <div style="display: flex; align-items: flex-start; margin-bottom: 15px;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#3182ce"
                                    style="min-width: 24px; margin-right: 15px; margin-top: 3px;">
                                    <path
                                        d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z" />
                                </svg>
                                <div>
                                    <h3 style="margin: 0 0 2px 0; font-size: 16px; color: #2d3748;">Verified Profiles
                                    </h3>
                                    <p style="margin: 0; font-size: 14px; color: #718096; line-height: 1.5;">Every
                                        profile is screened by our team to ensure authenticity and safety.</p>
                                </div>
                            </div>
                            <div style="display: flex; align-items: flex-start;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="#3182ce"
                                    style="min-width: 24px; margin-right: 15px; margin-top: 3px;">
                                    <path
                                        d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z" />
                                </svg>
                                <div>
                                    <h3 style="margin: 0 0 2px 0; font-size: 16px; color: #2d3748;">Privacy Controls
                                    </h3>
                                    <p style="margin: 0; font-size: 14px; color: #718096; line-height: 1.5;">You decide
                                        who sees your photos and contact information. Chat securely without sharing
                                        personal details.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- === BODY CONTENT END === -->

                    <!-- Footer -->
                    <!-- Footer (Unchanged) -->
                    <div class="footer">
                        <p><strong>Need help?</strong> Visit our <a href="https://usamarry.com/help"
                                style="color: #c05346;">Help Center</a> or reach out to us directly.</p>
                        <div class="contact-info">
                            <div><strong>Email:</strong> <a href="mailto:contact@usamarry.com">contact@usamarry.com</a>
                            </div>
                            <div><strong>Phone:</strong> +1 (888) 887 5027</div>
                            <div><strong>Office:</strong> 74-09 37TH Avenue, Suite 203B, Jackson Heights, NY 11372</div>
                        </div>
                        <p><a href="https://usamarry.com" style="color: #c05346;">www.usamarry.com</a></p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
