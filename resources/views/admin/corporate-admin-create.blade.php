<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Corporate Admin</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f7fb; color: #132033; }
        main { max-width: 720px; margin: 40px auto; padding: 24px; }
        .panel { background: #fff; border-radius: 12px; border: 1px solid #d7e2ef; padding: 24px; box-shadow: 0 12px 30px rgba(15,23,42,0.06); }
        h1 { margin-top: 0; }
        .muted { color: #58677a; }
        .field { margin-bottom: 16px; }
        label { display:block; font-weight:700; margin-bottom:8px; }
        input, textarea { width:100%; padding: 10px 12px; border:1px solid #cfd9e5; border-radius:8px; font: inherit; }
        textarea { min-height: 110px; resize: vertical; }
        button { background:#0f172a; color:#fff; border:0; border-radius:8px; padding:10px 16px; font-weight:700; cursor:pointer; }
        .notice { margin-bottom: 18px; padding: 12px; border-radius: 8px; background: #eefaf3; color: #165132; border: 1px solid #b7e2c9; }
        .error { margin-bottom: 18px; padding: 12px; border-radius: 8px; background: #fff1f2; color: #a12d3a; border: 1px solid #f1bec7; }
        .back-link { display: inline-block; margin-bottom: 16px; color: #0f172a; font-weight:700; text-decoration:none; }
    </style>
</head>
<body>
    <main>
        <a class="back-link" href="{{ route('admin.dashboard') }}">← Back to dashboard</a>
        <section class="panel">
            <h1>Register Corporate Admin</h1>
            <p class="muted">Create a corporate admin account and assign the ID cards they should manage. The default password will be set from the value below.</p>

            @if (session('success'))
                <div class="notice">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="error">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.corporate-admins.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                </div>

                <div class="field">
                    <label for="company_name">Company name</label>
                    <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required>
                </div>

                <div class="field">
                    <label for="email">Corporate admin email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                </div>

                <div class="field">
                    <label for="default_password">Default password</label>
                    <input id="default_password" name="default_password" type="text" value="{{ old('default_password') }}" placeholder="Welcome123!" required>
                </div>

                <div class="field">
                    <label for="card_count">Number of card IDs to generate</label>
                    <input id="card_count" name="card_count" type="number" min="0" max="1000" value="{{ old('card_count', 0) }}" required>
                    <p class="muted" style="margin-top: 6px; font-size: 13px;">Enter how many unique card IDs should be generated for this corporate admin. New orders will automatically generate additional IDs as needed.</p>
                </div>

                <button type="submit">Register corporate admin</button>
            </form>
        </section>
    </main>
</body>
</html>
