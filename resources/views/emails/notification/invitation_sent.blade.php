<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Usa Marry - Invitation Sent</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');

        body {
            font-family: 'Nunito Sans', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f5f7;
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
        .header {
            border-bottom: 1px solid #801b1b;
            text-align: center;
            color: #333;
            padding: 20px 0;
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
            margin-top: 20px;
            text-align: center;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: scale(1.05);
        }
        .profile-link {
            color: #c05346;
            text-decoration: none;
            font-weight: 600;
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
        .footer .contact-info {
            margin-top: 15px;
            font-size: 14px;
        }
        .footer .contact-info a {
            color: #c05346;
            text-decoration: none;
        }
        .footer .contact-info div {
            margin: 5px 0;
        }
        @media (max-width: 600px) {
            .footer .contact-info {
                font-size: 12px;
            }
            .profile-card-content {
                padding: 15px !important;
            }
            .profile-header {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .profile-pic-container {
                margin-bottom: 15px !important;
                margin-right: 0 !important;
            }
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
                    <img src="https://usamarry.com/_next/image?url=%2Fusa-marry-logo.png&w=384&q=75"
                         alt="Usa Marry Logo" style="width: 180px; filter: contrast(150%);" />
                </div>

                <!-- Body Content -->
                <div class="content" style="padding: 10px 0; text-align: center;">
                    <h1 style="font-size: 28px; font-weight: 800; color: #1a202c; margin: 0 0 10px 0;">
                        Your Invitation is on its Way!
                    </h1>

                    <p style="font-size: 16px; line-height: 1.6; color: #555; max-width: 500px; margin: 0 auto 25px auto;">
                        Hi {{ $user->name }}, we've successfully sent your connection request to
                        <strong>{{ $connection_user->name }}</strong>. We will notify you as soon as they respond.
                    </p>

                    <!-- Profile Card -->
                    <div class="profile-card" style="border: 1px solid #e2e8f0; border-radius: 12px; text-align: left; background-color: #ffffff;">
                        <div class="profile-card-content" style="padding: 25px;">
                            <div style="color: #718096; font-weight: 600; font-size: 14px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Invitation Sent To
                            </div>
                            <div class="profile-header" style="display: flex; align-items: center;">
                                <!-- Profile Picture Placeholder -->
                                <div class="profile-pic-container" style="min-width: 60px; height: 60px; border-radius: 50%; background-color: #e2e8f0; margin-right: 20px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    @if(!empty($profile_picture))
                                        <img src="{{ $profile_picture }}" alt="Profile Picture" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;" />
                                    @else
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="#718096"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8
                                                1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h2 style="margin: 0; font-size: 20px; color: #2d3748; font-weight: 700;">
                                        {{ $connection_user->name }}
                                    </h2>
                                    <div style="display: flex; align-items: center; margin-top: 5px; color: #718096; font-size: 14px;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 6px;">
                                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0
                                                9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5
                                                2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                        </svg>
                                        {{ $connection_location ?? 'Location not available' }}
                                    </div>
                                </div>
                            </div>
                            <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
                                <p style="margin: 0;">
                                    <a href="{{ $profileUrl }}" class="profile-link">View Full Profile Again →</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- CTA Section -->
                    <div style="text-align: center; margin-top: 30px; background-color: #f8f9fa; padding: 25px; border-radius: 8px;">
                        <h3 style="margin: 0 0 10px 0; font-size: 18px; font-weight: 700; color: #2d3748;">Don't Stop Your Search!</h3>
                        <p style="margin: 0 0 15px 0; font-size: 16px; color: #555;">
                            While you wait for a response, why not discover other potential matches?
                        </p>
                        <a href="https://usamarry.com/dashboard" class="button">Explore More Profiles</a>
                    </div>
                </div>

                <!-- Footer -->
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
        </td>
    </tr>
</table>
</body>
</html>
