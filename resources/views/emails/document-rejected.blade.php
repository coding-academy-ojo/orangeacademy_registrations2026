<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Registration Submitted</title>
</head>


<head>
    <meta charset="UTF-8">
    <title>Document Rejected</title>
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

                            <h2 style="margin-top:0;color:#dc3545;">Document Rejected ⚠️</h2>

                            <p>
                                Hello <strong>{{ $user->profile->first_name_en ?? 'Student' }}</strong>,
                            </p>

                            <p>
                                Unfortunately, the document you uploaded for <strong>"{{ $document_name }}"</strong> was
                                rejected by our administration.
                            </p>

                            <div style="
background:#fff5f5;
border:1px solid #feb2b2;
color:#c53030;
padding:20px;
border-radius:6px;
margin:20px 0;
text-align:left;
">
                                <strong>Reason for rejection:</strong><br>
                                {{ $reason }}
                            </div>

                            <p>
                                Please upload a clearer or more accurate version of this document to proceed with your
                                registration.
                            </p>

                            <a href="{{ $update_url }}" style="
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
                                Update Document
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