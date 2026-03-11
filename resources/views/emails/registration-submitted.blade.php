<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Registration Submitted</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#ff7900;padding:25px;text-align:center;">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Orange_logo.svg" width="120"
                                alt="Orange Logo">
                            <h2 style="color:#ffffff;margin-top:10px;">Orange Coding Academy</h2>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:40px;text-align:center;color:#333;">

                            <h2 style="margin-top:0;">Registration Submitted Successfully 🎉</h2>

                            <p>
                                Hello <strong>{{ $user->profile->first_name_en ?? 'Student' }}</strong>,
                            </p>

                            <p>
                                Congratulations! Your registration with <strong>Orange Coding Academy</strong> has been
                                successfully submitted.
                            </p>

                            <div
                                style="background:#f9f9f9; padding:20px; border-radius:8px; margin:20px 0; text-align:left; border-left:4px solid #ff7900;">
                                <h3 style="margin-top:0; color:#ff7900;">About Orange Jordan Academy</h3>
                                <p style="font-size:14px; line-height:1.6; color:#555;">
                                    Part of the Orange Digital Center initiative, our academy provides high-level
                                    digital training to empower the next generation of tech leaders in Jordan.
                                </p>
                            </div>

                            <p>
                                Our team is now reviewing your application. <strong>Please be patient</strong>, we will
                                connect with you as soon as possible via email or phone.
                            </p>

                            <a href="{{ $dashboard_url }}" style="
display:inline-block;
margin-top:20px;
background:#ff7900;
color:white;
text-decoration:none;
padding:14px 30px;
border-radius:6px;
font-weight:bold;
font-size:16px;
">
                                Go to Dashboard
                            </a>

                            <p style="margin-top:30px;">
                                If you have any questions, feel free to contact our team.
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#fafafa;padding:20px;text-align:center;font-size:13px;color:#777;">

                            <p>© {{ date('Y') }} Orange Coding Academy</p>
                            <p>Empowering the next generation of developers</p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>