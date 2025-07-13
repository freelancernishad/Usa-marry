<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Usa Marry - Invitation to Connect</title>
    <style>
        /* Your existing CSS */
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
            border: 0.5px solid #e6796d70;
            padding: 20px;
            box-sizing: border-box;
        }
        .primary-button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #28a745;
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            text-align: center;
            transition: transform 0.2s;
        }
        .primary-button:hover {
            transform: scale(1.05);
            background-color: #218838;
        }
        .secondary-link {
            display: inline-block;
            color: #d9534f;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 25px;
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
        @media (max-width: 480px) {
            .footer .contact-info {
                font-size: 12px;
            }
            .profile-card-content {
                padding: 15px !important;
            }
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            .primary-button,
            .secondary-link {
                width: 80%;
                box-sizing: border-box;
                margin-bottom: 10px;
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
                    <div class="header" style="display: flex; justify-content: space-between; align-items: center; color: #333; padding: 20px 0;">
                        <img src="https://usamarry.com/_next/image?url=%2Fusa-marry-logo.png&w=384&q=75" alt="Usa Marry Logo" style="width: 180px; filter: contrast(150%);" />
                    </div>

                    <!-- BODY CONTENT -->
                    <div class="content" style="padding: 10px 0; text-align: center;">
                        <!-- Main Heading -->
                        <h1 style="font-size: 28px; font-weight: 800; color: #1a202c; margin: 0 0 10px 0;">You've Received an Invitation!</h1>
                        <p style="font-size: 16px; line-height: 1.6; color: #555; max-width: 500px; margin: 0 auto 25px auto;">
                           Hi {{ $recipientName }}, <strong>{{ $senderName }} ({{ $senderCode }})</strong> would like to connect with you. A new connection could be just a click away!
                        </p>

                        <!-- Profile Card -->
                        <div class="profile-card" style="border: 1px solid #e2e8f0; border-radius: 12px; text-align: left; background-color: #f8f9fa;">
                            <div class="profile-card-content" style="padding: 25px;">
                                <div style="display: flex; align-items: center; margin-bottom: 20px;">
                                    <div style="min-width: 60px; height: 60px; border-radius: 50%; background-color: #e2e8f0; margin-right: 20px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        @if(!empty($profile_picture))
                                            <img src="{{ $profile_picture }}" alt="Profile Picture" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;" />
                                        @else
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="#718096"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h2 style="margin: 0; font-size: 20px; color: #2d3748; font-weight: 700;">{{ $senderName }}</h2>
                                        <div style="display: flex; align-items: center; margin-top: 5px; color: #718096; font-size: 14px;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 6px;">
                                                <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                                            </svg>
                                            {{ $senderLocation }}
                                        </div>
                                    </div>
                                </div>
                                <div style="border-top: 1px solid #e2e8f0; padding-top: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                                    <div style="display: flex; align-items: center; font-size: 14px; color: #4a5568;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px; color: #a0aec0;">
                                            <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                                        </svg>
                                        <strong>Age:</strong> {{ $senderAge }}
                                    </div>
                                    <div style="display: flex; align-items: center; font-size: 14px; color: #4a5568;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px; color: #a0aec0;">
                                            <path d="M7.75 6.06H5.25v11.88h2.5V6.06M18.75 6.06h-2.5v11.88h2.5V6.06m-5.5-2.06h-2.5v15.94h2.5V4M21.25 2H2.75v20h18.5V2z"/>
                                        </svg>
                                        <strong>Height:</strong> {{ $senderHeight }}
                                    </div>
                                    <div style="display: flex; align-items: center; font-size: 14px; color: #4a5568;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px; color: #a0aec0;">
                                            <path d="M18.42 12.3L17.7 17.58L12 21.42L6.3 17.58L5.58 12.3L12 10.05M22.8 11.1L12.9 2.25A1.2 1.2 0 0 0 12 1.8A1.2 1.2 0 0 0 11.1 2.25L1.2 11.1A1.2 1.2 0 0 0 1.8 12.9L4.86 19.35A1.2 1.2 0 0 0 6 20.22L12 24L18 20.22A1.2 1.2 0 0 0 19.14 19.35L22.2 12.9A1.2 1.2 0 0 0 22.8 11.1Z"/>
                                        </svg>
                                        <strong>Religion:</strong> {{ $senderReligion }}
                                    </div>
                                    <div style="display: flex; align-items: center; font-size: 14px; color: #4a5568;">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px; color: #a0aec0;">
                                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                        </svg>
                                        <strong>Caste:</strong> {{ $senderCaste }}
                                    </div>
                                </div>
                                <div style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 15px; text-align: center;">
                                    <a href="{{ $profileUrl }}" style="color: #c05346; text-decoration: none; font-weight: 600;">View Full Profile for More Details →</a>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons" style="text-align: center; margin-top: 30px; display: flex; justify-content: center; align-items: center; gap: 15px;">
                            <a href="{{ $declineUrl }}" class="secondary-link">Decline</a>
                            <a href="{{ $acceptUrl }}" class="primary-button">Accept Invitation</a>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="footer">
                        <p>Need help? Visit our <a href="https://usamarry.com/help">Help Center</a> or reach out to us directly.</p>
                        <p>The <strong>Usa Marry</strong> Team</p>
                        <p><a href="https://usamarry.com" style="color: #c05346;">www.usamarry.com</a></p>
                        <p>If you no longer wish to receive these emails, <a href="https://usamarry.com/unsubscribe" style="color: #c05346;">unsubscribe here</a>.</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
