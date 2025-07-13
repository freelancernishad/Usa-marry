<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Usa Marry - Connection Accepted</title>
    <style>
        /* Same styles as above — you can move styles to a shared file or inline for emails */
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700;800&display=swap');
        body {
            font-family: 'Nunito Sans', sans-serif;
            color: #333;
            margin: 0; padding: 0;
            background-color: #f4f5f7;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            border: 0.5px solid #e6796d70;
            padding: 20px;
            box-sizing: border-box;
        }
        .header {
            border-bottom: 1px solid #801b1b;
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 0;
            color: #333;
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
        .button:hover { transform: scale(1.05); }
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
            border-top: 1px solid #eee;
        }
        .footer p { margin: 5px 0; }
        .footer .contact-info {
            margin-top: 15px;
            font-size: 14px;
        }
        .footer .contact-info a {
            color: #c05346;
            text-decoration: none;
        }
        .footer .contact-info div { margin: 5px 0; }
        @media (max-width: 600px) {
            .footer .contact-info { font-size: 12px; }
            .profile-card-content { padding: 15px !important; }
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
                    <div class="header">
                        <img src="https://usamarry.com/_next/image?url=%2Fusa-marry-logo.png&w=384&q=75" alt="Usa Marry Logo" style="width: 180px; filter: contrast(150%);" />
                    </div>
                    <div class="content" style="padding: 10px 0; text-align: center;">
                        <h1 style="font-size: 28px; font-weight: 800; color: #1a202c; margin: 0 0 10px 0;">
                            Congratulations, {{ $receiverName }}!
                        </h1>
                        <p style="font-size: 16px; line-height: 1.6; color: #555; max-width: 500px; margin: 0 auto 25px auto;">
                            You have successfully accepted the connection request from <strong>{{ $senderName }}</strong>. You are now connected.
                        </p>

                        <!-- Profile Card -->
                        <div class="profile-card" style="border: 1px solid #e2e8f0; border-radius: 12px; text-align: left; background-color: #f8f9fa;">
                            <div class="profile-card-content" style="padding: 25px;">
                                <div class="profile-header" style="display: flex; align-items: center; margin-bottom: 20px;">
                                    @if(!empty($profile_picture))
                                        <div class="profile-pic-container" style="min-width: 70px; height: 70px; border-radius: 50%; background-color: #e2e8f0; margin-right: 20px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            <img src="{{ $profile_picture }}" alt="{{ $senderName }}'s Profile Picture" style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%;" />
                                        </div>
                                    @else
                                        <div class="profile-pic-container" style="min-width: 70px; height: 70px; border-radius: 50%; background-color: #e2e8f0; margin-right: 20px; display: flex; align-items: center; justify-content: center;">
                                            <svg width="36" height="36" viewBox="0 0 24 24" fill="#718096" xmlns="http://www.w3.org/2000/svg"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h2 style="margin: 0; font-size: 22px; color: #2d3748; font-weight: 700;">{{ $senderName }}</h2>
                                        <div style="display: flex; align-items: center; margin-top: 5px; color: #718096; font-size: 15px;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 6px;"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                            {{ $senderLocation }}
                                        </div>
                                    </div>
                                </div>
                                <div style="border-top: 1px solid #e2e8f0; padding-top: 15px;">
                                    <div style="display: flex; align-items: center; margin-bottom: 10px; font-size: 15px; color: #4a5568;">
                                        <svg width="20" height="20" fill="currentColor" style="margin-right: 10px; color: #718096;"><path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/></svg>
                                        <strong>Age:</strong> {{ $senderAge }}
                                    </div>
                                    <div style="display: flex; align-items: center; margin-bottom: 10px; font-size: 15px; color: #4a5568;">
                                        <svg width="20" height="20" fill="currentColor" style="margin-right: 10px; color: #718096;"><path d="M7.75 6.06H5.25v11.88h2.5V6.06M18.75 6.06h-2.5v11.88h2.5V6.06m-5.5-2.06h-2.5v15.94h2.5V4M21.25 2H2.75v20h18.5V2z"/></svg>
                                        <strong>Height:</strong> {{ $senderHeight }}
                                    </div>
                                    <div style="display: flex; align-items: center; font-size: 15px; color: #4a5568;">
                                        <svg width="20" height="20" fill="currentColor" style="margin-right: 10px; color: #718096;"><path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-2 .89-2 2v11c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zM10 4h4v2h-4V4zm10 15H4V8h16v11z"/></svg>
                                        <strong>Occupation:</strong> {{ $senderOccupation }}
                                    </div>
                                </div>
                                <div style="margin-top: 20px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                                    <p style="margin: 0; font-size: 15px; line-height: 1.6; color: #4a5568; font-style: italic;">
                                        "{{ $senderBioSnippet }}"
                                    </p>
                                    <p style="margin: 10px 0 0 0;"><a href="{{ $senderProfileUrl }}" class="profile-link">Read Full Profile →</a></p>
                                </div>
                            </div>
                        </div>

                        <div style="text-align: center; margin-top: 30px;">
                            <p style="margin: 0 0 15px 0; font-size: 16px; color: #555;">Start your conversation today!</p>
                            <a href="{{ $senderProfileUrl }}" class="button">Say Hello to {{ $senderName }}</a>
                        </div>
                    </div>

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
