<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — FinanceZen</title>
    <style>
        body { margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #F1F5F9; color: #334155; line-height: 1.6; }
        .wrapper { max-width: 600px; margin: 40px auto; background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.06); }
        .header { background: linear-gradient(135deg, #4F46E5, #6366F1); padding: 32px; text-align: center; }
        .header h1 { color: white; font-size: 24px; margin: 0; font-weight: 800; letter-spacing: -0.5px; }
        .header .logo { font-size: 28px; font-weight: 900; color: white; margin-bottom: 8px; }
        .body { padding: 32px; }
        .body h2 { color: #1E293B; font-size: 20px; font-weight: 800; margin: 0 0 16px; }
        .body p { color: #64748B; font-size: 15px; margin: 0 0 16px; }
        .btn { display: inline-block; padding: 14px 32px; background: #4F46E5; color: white; text-decoration: none; font-weight: 700; border-radius: 12px; font-size: 15px; margin: 16px 0; }
        .btn:hover { background: #4338CA; }
        .btn-danger { background: #EF4444; }
        .highlight { background: #F8FAFC; border-radius: 12px; padding: 20px; border: 1px solid #E2E8F0; margin: 16px 0; }
        .highlight strong { color: #1E293B; font-size: 18px; }
        .footer { padding: 24px 32px; text-align: center; border-top: 1px solid #F1F5F9; background: #F8FAFC; }
        .footer p { color: #94A3B8; font-size: 12px; margin: 4px 0; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">💰 FinanceZen</div>
            <h1>@yield('title')</h1>
        </div>
        <div class="body">
            @yield('content')
        </div>
        <div class="footer">
            <p><strong>FinanceZen</strong> — Gérez vos finances en toute sérénité</p>
            <p>Cet email vous a été envoyé automatiquement. Ne pas répondre.</p>
        </div>
    </div>
</body>
</html>
