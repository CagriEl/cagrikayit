<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Çağrı Kayıt</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
        }
        .wrap {
            width: min(720px, calc(100% - 32px));
            text-align: center;
        }
        h1 { margin: 0 0 8px; font-size: 2rem; }
        p { margin: 0 0 28px; color: #94a3b8; }
        .cards {
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }
        a {
            display: block;
            padding: 24px;
            border-radius: 16px;
            text-decoration: none;
            color: inherit;
            background: #1e293b;
            border: 1px solid #334155;
        }
        a:hover { border-color: #64748b; }
        strong { display: block; font-size: 1.15rem; margin-bottom: 8px; }
        span { color: #94a3b8; font-size: 0.95rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <h1>Çağrı Kayıt</h1>
        <p>Giriş yapmak istediğiniz paneli seçin.</p>
        <div class="cards">
            <a href="{{ url('/personel/login') }}">
                <strong>Personel paneli</strong>
                <span>Çağrı kaydı oluşturmak için giriş yapın.</span>
            </a>
            <a href="{{ url('/admin/login') }}">
                <strong>Yönetim paneli</strong>
                <span>Personel tanımlamak için yönetici girişi.</span>
            </a>
        </div>
    </div>
</body>
</html>
