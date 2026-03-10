<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Orange Academy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .btn-orange {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border: none;
            color: white;
            font-weight: 600;
        }
        .btn-orange:hover {
            background: linear-gradient(135deg, #e55a2b 0%, #e5851a 100%);
            color: white;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card p-4">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Verify Your Email</h3>
                        <p class="text-muted">We've sent a verification code to<br><strong>{{ auth()->user()->email }}</strong></p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.verify-email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Verification Code</label>
                            <input type="text" name="code" class="form-control text-center fs-4 letter-spacing" 
                                   placeholder="XXXXXX" maxlength="6" required autofocus
                                   style="letter-spacing: 5px;">
                            @error('code')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-orange w-100 py-2">Verify Email</button>
                    </form>

                    <div class="text-center mt-3">
                        <form method="POST" action="{{ route('student.verify-email.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none">Resend Code</button>
                        </form>
                    </div>

                    <div class="text-center mt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none text-muted">Sign out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
