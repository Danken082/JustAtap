<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account | Smart Tap</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d0f14;
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
            background: radial-gradient(circle at 10% 8%, rgba(76, 122, 255, 0.32), transparent 34%),
                        radial-gradient(circle at 92% 85%, rgba(255, 121, 70, 0.2), transparent 42%),
                        var(--bg);
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .register-card {
            width: min(500px, 100%);
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

        input[type="text"],
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
            margin-top: 4px;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .switch-auth {
            margin-top: 14px;
            color: var(--muted);
            font-size: 0.9rem;
        }

        .switch-auth a,
        .back {
            color: #edf2ff;
            font-weight: 700;
            text-decoration: none;
        }

        .back {
            display: inline-block;
            margin-top: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #ccd5ec;
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
    </style>
</head>
<body>
    <section class="register-card">
        <a href="{{ route('home') }}" class="brand">
            <span class="brand-mark">S</span>
            Smart Tap
        </a>

        <h1>Create account</h1>
        <p class="subtitle">Set up your Smart Tap account and start networking.</p>

        @if ($errors->any())
            <div class="error-box">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register.attempt') }}">
            @csrf

            <div class="field">
                <label for="name">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            </div>

            <div class="field">
                <label for="card_id">Card ID</label>
                <input id="card_id" type="text" name="card_id" value="{{ old('card_id') }}" required autocomplete="card_id" autofocus>
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password">
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn">Create account</button>
        </form>

        <p class="switch-auth">Already have an account? <a href="{{ route('login') }}">Login</a></p>
        <a href="{{ route('home') }}" class="back">Back to home</a>
    </section>
</body>
</html>
