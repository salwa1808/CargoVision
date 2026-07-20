<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Global Supply Chain Risk Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-body: radial-gradient(circle at 10% 20%, #1c0f38 0%, #0d061c 50%, #05020c 100%);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-primary: linear-gradient(135deg, #8b5cf6, #a78bfa);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-body) !important;
            color: var(--text-main) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.4);
            padding: 40px;
        }

        .brand-logo-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
            margin-top: 15px;
        }

        .brand-subtitle {
            font-size: 10px;
            font-weight: 800;
            color: #a78bfa;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.03) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border-radius: 12px !important;
            padding: 12px 16px !important;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2) !important;
            border-color: rgba(139, 92, 246, 0.4) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 12px !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px;
            transition: all 0.2s ease !important;
        }

        .btn-primary:hover {
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.5) !important;
            transform: translateY(-1px) !important;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.04) !important;
            backdrop-filter: blur(8px) !important;
            -webkit-backdrop-filter: blur(8px) !important;
            border: 1px solid rgba(255, 255, 255, 0.08) !important;
            border-radius: 12px !important;
            color: #ffffff !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            padding: 10px 16px !important;
            transition: all 0.2s ease !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.12) !important;
            border-color: rgba(255, 255, 255, 0.2) !important;
            transform: translateY(-1px) !important;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: rgba(255, 255, 255, 0.15);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 25px 0;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .divider:not(:empty)::before {
            margin-right: 15px;
        }

        .divider:not(:empty)::after {
            margin-left: 15px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="glass-card">
        <div class="brand-logo-wrapper">
            <svg width="40" height="46" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0L2 4V13C2 19.3 6.3 25.1 12 28C17.7 25.1 22 19.3 22 13V4L12 0Z" fill="#ff7a00"/>
                <path d="M12 2.5L3.8 5.8V13.2C3.8 18.5 7.4 23.3 12 25.8V2.5Z" fill="#ff9900"/>
            </svg>
            <span class="brand-title">SupplyGuard Risk</span>
            <span class="brand-subtitle">Authentication Portal</span>
        </div>

        @if($errors->any())
            <div class="alert alert-danger border-0 small text-start" style="border-radius: 12px; background-color: rgba(239, 68, 68, 0.15); color: #f87171;">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="name@domain.com" value="{{ old('email') }}" required autofocus>
            </div>
            
            <div class="mb-4">
                <label class="form-label text-muted small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Sign In
            </button>
        </form>

        <div class="divider">Demo Accounts</div>

        <div class="d-flex flex-column gap-2">
            <button class="btn btn-glass w-100" onclick="preFill('admin@example.com')">
                🛡️ Sign In as Administrator
            </button>
            <button class="btn btn-glass w-100" onclick="preFill('user@example.com')">
                👤 Sign In as Standard User
            </button>
        </div>

        <div class="text-center mt-4">
            <span class="text-muted small">New user? </span>
            <a href="{{ url('/register') }}" class="text-decoration-none small" style="color: #c084fc; font-weight: 600;">Create account</a>
        </div>
    </div>
</div>

<script>
    function preFill(email) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = 'password';
        // Auto submit
        document.querySelector('form').submit();
    }
</script>
</body>
</html>
