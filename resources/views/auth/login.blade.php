<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — CargoVision</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-body: radial-gradient(circle at 15% 15%, #0b3550 0%, #071a2b 45%, #030b16 100%);
            --border-color: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-primary: linear-gradient(135deg, #06b6d4, #2563eb);
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
        body::before{content:'';position:fixed;inset:0;pointer-events:none;background:linear-gradient(rgba(103,232,249,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(103,232,249,.025) 1px,transparent 1px);background-size:44px 44px;mask-image:linear-gradient(to bottom,black,transparent 75%)}

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .glass-card {
            background: linear-gradient(145deg, rgba(15,35,53,.88), rgba(4,18,32,.82));
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            box-shadow: 0 28px 80px rgba(0,0,0,.42), inset 0 1px rgba(255,255,255,.05);
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
            color: #67e8f9;
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
            box-shadow: 0 0 0 3px rgba(6,182,212,.16) !important;
            border-color: rgba(34,211,238,.45) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #06b6d4, #2563eb) !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 12px !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px;
            transition: all 0.2s ease !important;
        }

        .btn-primary:hover {
            box-shadow: 0 10px 28px rgba(6,182,212,.32) !important;
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
            <svg width="54" height="54" viewBox="0 0 32 32" fill="none" style="color:#67e8f9;filter:drop-shadow(0 8px 18px rgba(6,182,212,.3))"><path d="M5 18h22l-3.2 6.2A5 5 0 0 1 19.4 27h-7.8a5 5 0 0 1-4.4-2.8L5 18Z" fill="currentColor"/><path d="M9 10h14v8H9zM13 6h6v4h-6zM3 14h26M3 29c3-2 5-2 8 0s5 2 8 0 5-2 10 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
            <span class="brand-title">CargoVision</span>
            <span class="brand-subtitle">Maritime Intelligence Portal</span>
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
            <a href="{{ url('/register') }}" class="text-decoration-none small" style="color: #67e8f9; font-weight: 600;">Create account</a>
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
