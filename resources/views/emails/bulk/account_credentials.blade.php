<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your FREE Essential Plan is Ready!</title>
    <!-- Basic styling for desktop and mobile -->
    <style>
        /* Font Import (best effort for email) */
        @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700;800&display=swap');

        /* Reset styles for better compatibility */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            margin: 0;
            padding: 0;
            font-family: 'Nunito Sans', Arial, sans-serif;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        /* Ensure links use inherited styles */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f5f7; font-family: 'Nunito Sans', Arial, sans-serif;">

    <!-- Outer Table for Centering and Background -->
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; background-color: #f4f5f7;">
        <tr>
            <td align="center" style="padding: 20px 0;">

                <!-- Email Content Container (Max Width 600px) -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%"
                       style="max-width: 600px; background-color: #ffffff; border-radius: 8px; border: 0.5px solid #e6796d70; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); border-collapse: collapse;">

                    <!-- Header/Logo Row with bottom divider -->
                    <tr>
                        <!-- Added border-bottom and padding to match the Usa Marry header style -->
                        <td align="center" style="padding: 20px 30px 15px 30px; border-bottom: 1px solid #801b1b;">
                            <img src="https://usamarry.com/_next/image?url=%2Fusa-marry-logo.png&w=384&q=75"
                                alt="Usa Marry Logo" style="width: 180px; height: auto; display: block; filter: contrast(150%); margin: 0 auto;">
                        </td>
                    </tr>

                    <!-- Header/Subject Line -->
                    <!-- <tr>
                        <td align="left" style="padding: 20px 30px 10px 30px; font-family: 'Nunito Sans', Arial, sans-serif; font-size: 24px; font-weight: 800; color: #1a202c;">
                            🎉 Welcome! Your FREE Essential Plan is ready
                        </td>
                    </tr> -->

                    <!-- Body Content -->
                    <tr>
                        <td align="left" style="padding: 10px 30px; font-family: 'Nunito Sans', Arial, sans-serif; font-size: 16px; line-height: 26px; color: #333333;">
                            Hi {{ $user->name }},
                            <br><br>
                            Great news! 🎉 You’ve been selected as one of the first 1,000 members to join our new platform.
                            <br><br>
                            We’ve already set up your profile for you, and as a thank-you gift, we’ve unlocked the **Essential Plan — FREE** (no payment required).
                            <br>
                            Here are your login details:
                        </td>
                    </tr>

                    <!-- Login Details Box -->
                    <tr>
                        <td align="center" style="padding: 10px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                   style="background-color: #fff8f7; border: 1px solid #e6796d; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 15px 20px; font-family: 'Nunito Sans', Arial, sans-serif; font-size: 16px; color: #000000; line-height: 24px;">
                                        <span style="font-weight: 700; color: #555555;">Username/Email:</span>
                                        <br>
                                        <span style="font-size: 17px; font-weight: 800; color: #c05346;">{{ $user->email }}</span>
                                        <br><br>
                                        <span style="font-weight: 700; color: #555555;">Temporary Password:</span>
                                        <br>
                                        <span style="font-size: 17px; font-weight: 800; color: #c05346; letter-spacing: 1px;">Password123</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Call to Action (Login Button) -->
                    <tr>
                        <td align="center" style="padding: 25px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                                <tr>
                                    <td align="center" style="border-radius: 8px; background-color: #c05346; padding: 14px 28px;">
                                        <a href="https://usamarry.com/login" target="_blank"
                                           style="font-size: 16px; font-family: 'Nunito Sans', Arial, sans-serif; font-weight: 700; color: #ffffff; text-decoration: none; display: inline-block; transition: transform 0.2s;">
                                            👉 Log in and start exploring
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Plan Benefits Section -->
                    <tr>
                        <td align="left" style="padding: 10px 30px 5px 30px; font-family: 'Nunito Sans', Arial, sans-serif; font-size: 16px; line-height: 26px; color: #333333;">
                            With your Essential Plan, you’ll be able to:
                        </td>
                    </tr>

                    <!-- Benefits List (using table for spacing/compatibility) -->
                    <tr>
                        <td align="left" style="padding: 0 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-family: 'Nunito Sans', Arial, sans-serif; font-size: 16px; color: #333333; line-height: 26px;">
                                <tr><td style="padding: 3px 0;"><span style="color: #c05346; font-weight: 700;">&#9658;</span> Send unlimited messages</td></tr>
                                <tr><td style="padding: 3px 0;"><span style="color: #c05346; font-weight: 700;">&#9658;</span> View up to 50 contact details</td></tr>
                                <tr><td style="padding: 3px 0;"><span style="color: #c05346; font-weight: 700;">&#9658;</span> Stand out from other profiles</td></tr>
                                <tr><td style="padding: 3px 0;"><span style="color: #c05346; font-weight: 700;">&#9658;</span> Let matches contact you directly</td></tr>
                                <tr><td style="padding: 3px 0;"><span style="color: #c05346; font-weight: 700;">&#9658;</span> Enjoy basic customer support</td></tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Closing -->
                    <tr>
                        <td align="left" style="padding: 20px 30px; font-family: 'Nunito Sans', Arial, sans-serif; font-size: 16px; line-height: 26px; color: #333333;">
                            We’re excited to see you connect and explore! 💙


                        </td>
                    </tr>

                    <!-- Footer/Unsubscribe Section -->
                    <tr>
                        <td align="center" style="padding: 0 30px 20px 30px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="font-family: 'Nunito Sans', Arial, sans-serif; font-size: 14px; color: #777777; line-height: 22px; text-align: center;">
                                <tr>
                                    <td style="padding: 15px 0; border-top: 1px solid #eeeeee;">
                                        Your choice matters.
                                        <br>
                                        If this isn’t for you, you can permanently delete your profile at any time. All of your data will be removed from our system.
                                        <br><br>
                                        <a href="https://usamarry.com/delete-account" target="_blank"
                                           style="color: #dc3545; text-decoration: underline; font-weight: 700;">
                                            Delete my profile
                                        </a>
                                        <span style="padding: 0 5px;">|</span>
                                        <a href="#" target="_blank" style="color: #777777; text-decoration: underline;">
                                            Unsubscribe
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>

                    </tr>



                </table>
                <!-- End Email Content Container -->

            </td>
        </tr>
    </table>
    <!-- End Outer Table -->

</body>
</html>
