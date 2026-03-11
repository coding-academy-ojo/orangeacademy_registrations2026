<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Phone - Orange Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange-primary: #ff6b35;
            --orange-dark: #e55a2b;
            --orange-light: #ff9d42;
            --orange-gradient: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #0a0a0f;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .page-bg {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .page-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(255, 121, 0, 0.12) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite;
        }

        .page-bg::after {
            content: '';
            position: absolute;
            bottom: -20%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 121, 0, 0.08) 0%, transparent 70%);
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .page-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .verify-card {
            background: #141419;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            max-width: 440px;
            width: 100%;
        }

        .verify-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--orange-gradient);
        }

        .verify-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 121, 0, 0.12);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            color: #ff9d42;
            font-size: 2rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .verify-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: white;
            margin-bottom: 12px;
            text-align: center;
        }

        .verify-desc {
            font-size: 0.95rem;
            color: rgba(255, 255, 255, 0.5);
            text-align: center;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .verify-desc strong {
            color: #ff9d42;
            font-weight: 600;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 16px 20px;
            color: white;
            font-size: 1.5rem;
            text-align: center;
            letter-spacing: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.2);
            letter-spacing: 4px;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 121, 0, 0.5);
            box-shadow: 0 0 0 4px rgba(255, 121, 0, 0.15);
            color: white;
            outline: none;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            margin-bottom: 10px;
            font-size: 0.9rem;
        }

        .btn-verify {
            background: var(--orange-gradient);
            border: none;
            color: white;
            font-weight: 600;
            padding: 16px 32px;
            border-radius: 12px;
            font-size: 1rem;
            width: 100%;
            margin-top: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 14px rgba(255, 121, 0, 0.35);
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 121, 0, 0.45);
            color: white;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 28px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        .divider span {
            padding: 0 16px;
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.8rem;
        }

        .resend-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: rgba(255, 255, 255, 0.6);
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 0.9rem;
            width: 100%;
            transition: all 0.3s;
        }

        .resend-btn:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 121, 0, 0.3);
            color: #ff9d42;
        }

        .logout-btn {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.35);
            font-size: 0.85rem;
            padding: 12px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .logout-btn:hover {
            color: #ff6b35;
        }

        .alert {
            border-radius: 12px;
            padding: 14px 18px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #75b798;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ea868f;
        }

        .phone-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: rgba(255, 121, 0, 0.1);
            border-radius: 10px;
            color: #ff9d42;
            margin-bottom: 8px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .verify-card {
            animation: fadeInUp 0.5s ease;
        }

        .verify-icon {
            animation: fadeInUp 0.5s ease 0.1s both;
        }

        .verify-title {
            animation: fadeInUp 0.5s ease 0.15s both;
        }

        .verify-desc {
            animation: fadeInUp 0.5s ease 0.2s both;
        }

        .form-group-anim {
            animation: fadeInUp 0.5s ease 0.25s both;
        }

        .btn-verify {
            animation: fadeInUp 0.5s ease 0.3s both;
        }
    </style>
</head>

<body>
    <div class="page-bg">
        <div class="page-grid"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="verify-card">
                    <div class="verify-icon">
                        <i class="bi bi-phone-fill"></i>
                    </div>

                    <h3 class="verify-title">Verify Your Phone</h3>
                    <p class="verify-desc">
                        We've sent a verification code to your phone number<br>
                        <strong>{{ auth()->user()->profile->phone }}</strong>
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.verify-phone') }}">
                        @csrf
                        <div class="mb-3 form-group-anim">
                            <label class="form-label d-flex align-items-center justify-content-center gap-2">
                                <span class="phone-icon"><i class="bi bi-shield-check"></i></span>
                                Enter Verification Code
                            </label>
                            <input type="text" name="code" class="form-control" 
                                placeholder="000000" maxlength="6" required autofocus>
                            @error('code')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-verify">
                            <i class="bi bi-check-lg me-2"></i>
                            Verify Phone
                        </button>
                    </form>

                    <div class="divider">
                        <span>Didn't receive code?</span>
                    </div>

                    <form method="POST" action="{{ route('student.verify-phone.resend') }}">
                        @csrf
                        <button type="submit" class="resend-btn">
                            <i class="bi bi-arrow-clockwise me-2"></i>
                            Resend Code
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="bi bi-box-arrow-left me-1"></i>
                                Sign out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelector('input[name="code"]').addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>

</html>
