<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Smart Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d0f14;
            --panel: #111520;
            --card: #171b27;
            --text: #f5f7ff;
            --muted: #9ea7bc;
            --line: rgba(255, 255, 255, 0.12);
            --accent: #edf2ff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: var(--text);
            background: radial-gradient(circle at 85% 10%, rgba(76, 122, 255, 0.32), transparent 36%),
                        radial-gradient(circle at 8% 88%, rgba(255, 121, 70, 0.25), transparent 40%),
                        var(--bg);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .login-card {
            width: min(460px, 100%);
            background: linear-gradient(170deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.01));
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 28px;
            backdrop-filter: blur(8px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.35);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            text-decoration: none;
            color: var(--text);
            font-weight: 800;
            letter-spacing: 0.12em;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(145deg, #edf2ff, #88a3ff);
            display: grid;
            place-items: center;
            color: #11131a;
            font-weight: 800;
        }

        h1 {
            margin: 0 0 8px;
            font-size: clamp(1.6rem, 4vw, 2rem);
        }

        .subtitle {
            margin: 0 0 24px;
            color: var(--muted);
            font-size: 0.96rem;
        }

        .field {
            margin-bottom: 14px;
        }

        label {
            display: block;
            font-size: 0.88rem;
            margin-bottom: 7px;
            color: #dce1ef;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--line);
            border-radius: 12px;
            color: var(--text);
            font-size: 1rem;
            padding: 12px 14px;
            outline: none;
        }

        input:focus {
            border-color: rgba(165, 187, 255, 0.75);
            box-shadow: 0 0 0 3px rgba(136, 163, 255, 0.22);
        }

        .meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 12px 0 18px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 999px;
            padding: 12px 18px;
            font-size: 0.96rem;
            font-weight: 700;
            background: var(--accent);
            color: #121722;
            cursor: pointer;
            transition: transform 160ms ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .back {
            display: inline-block;
            margin-top: 16px;
            color: #ccd5ec;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .switch-auth {
            margin-top: 14px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .switch-auth a {
            color: #edf2ff;
            font-weight: 700;
            text-decoration: none;
        }

        .error-box {
            margin-bottom: 14px;
            border-radius: 10px;
            padding: 10px 12px;
            background: rgba(255, 116, 116, 0.14);
            border: 1px solid rgba(255, 116, 116, 0.4);
            color: #ffd7d7;
            font-size: 0.9rem;
        }

        @media (max-width: 560px) {
            body {
                padding: 16px;
            }

            .login-card {
                padding: 20px 16px;
                border-radius: 18px;
            }

            .brand {
                letter-spacing: 0.08em;
                font-size: 0.76rem;
            }

            .meta {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <section class="login-card">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark">S</span>
            Smart Tap
        </a>

        <h1>Welcome back</h1>
        <p class="subtitle">Login to manage your digital card profile and leads.</p>

        @if ($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>

            <div class="meta">
                <label class="remember">
                    <input type="checkbox" name="remember" value="1">
                    Remember me
                </label>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <p class="switch-auth">New user? <a href="{{ route('register') }}">Create account</a></p>

        <a href="{{ route('home') }}" class="back">Back to home</a>
    </section>
</body>
</html>
