<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Email Verification</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4;padding:40px 0;">
<tr>
<td align="center">

<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 5px 15px rgba(0,0,0,0.08);">

<!-- Header -->
<tr>
<td style="background:#ff7900;padding:25px;text-align:center;">
<img src="https://upload.wikimedia.org/wikipedia/commons/7/72/Orange_logo.svg" width="120" alt="Orange Logo">
<h2 style="color:#ffffff;margin-top:10px;">Orange Coding Academy</h2>
</td>
</tr>

<!-- Content -->
<tr>
<td style="padding:40px;text-align:center;color:#333;">

<h2 style="margin-top:0;">Verify Your Email Address</h2>

<p>
Thank you for registering with <strong>Orange Coding Academy</strong>.
</p>

<p>Your verification code is:</p>

<div style="
display:inline-block;
background:#ff7900;
color:white;
font-size:28px;
letter-spacing:5px;
padding:15px 30px;
border-radius:6px;
font-weight:bold;
margin:20px 0;
">
{{ $code }}
</div>

<p>
Enter this code in the registration page to verify your email.
</p>

<p style="color:#888;font-size:14px;">
This code will expire in 30 minutes.
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