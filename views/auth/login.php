<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
        :root {
            --primary:#00923F; --primary-hover:#00A548; --primary-dark:#007532;
            --bg:#F5F7FA; --card:#FFFFFF; --text:#1F2937; --text-muted:#6B7280;
            --stroke:#E5E7EB; --input-bg:#FFFFFF; --shadow:0 8px 30px rgba(0,0,0,.06);
        }
        body {
            font-family:'Poppins',system-ui,-apple-system,sans-serif;
            background:var(--bg);
            color:var(--text);
            min-height:100vh;
            display:flex; align-items:center; justify-content:center;
            padding:24px;
            -webkit-font-smoothing:antialiased;
        }
        .login-shell {
            width:100%;
            max-width:960px;
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:60px;
            align-items:center;
        }
        .brand-side {
            display:flex; align-items:center; justify-content:center;
            padding:20px;
        }
        .brand-side img {
            width:100%;
            max-width:340px;
            height:auto;
            object-fit:contain;
            filter:drop-shadow(0 6px 16px rgba(0,0,0,.08));
            transition:transform .4s ease;
        }
        .brand-side img:hover { transform:scale(1.03); }
        .brand-fallback {
            width:280px; height:280px; border-radius:50%;
            background:linear-gradient(135deg,var(--primary),var(--primary-dark));
            color:#fff; display:flex; align-items:center; justify-content:center;
            flex-direction:column; text-align:center; padding:20px;
            box-shadow:0 12px 40px rgba(0,146,63,.3);
        }
        .brand-fallback h1 { font-size:38px; font-weight:800; letter-spacing:2px; margin-bottom:6px; }
        .brand-fallback p { font-size:13px; font-weight:500; opacity:.9; letter-spacing:1px; }

        .form-side {
            background:var(--card);
            border-radius:16px;
            padding:40px 36px;
            box-shadow:var(--shadow);
            border:1px solid #F0F2F5;
        }
        .login-title {
            font-size:19px; font-weight:700; color:var(--primary);
            text-align:center; line-height:1.4;
            margin-bottom:28px; letter-spacing:.3px;
        }
        .alert.error {
            background:#FEF2F2; color:#B32020; padding:11px 14px;
            border-radius:8px; font-size:13px; margin-bottom:16px;
            border-left:3px solid #DC3545;
            animation:shake .35s ease;
        }
        @keyframes shake {
            0%,100% { transform:translateX(0); }
            25% { transform:translateX(-4px); }
            75% { transform:translateX(4px); }
        }
        .form-group { margin-bottom:16px; }
        .form-group label {
            display:block; font-size:11.5px; font-weight:600;
            color:var(--text-muted); text-transform:uppercase; letter-spacing:1px;
            margin-bottom:6px;
        }
        .form-control {
            width:100%;
            padding:11px 14px;
            font-size:14px; font-family:inherit;
            color:var(--text);
            background:var(--input-bg);
            border:1px solid var(--stroke);
            border-radius:8px;
            outline:none;
            transition:border-color .2s, box-shadow .2s;
        }
        .form-control::placeholder { color:#9CA3AF; font-size:13px; }
        .form-control:focus {
            border-color:var(--primary);
            box-shadow:0 0 0 3px rgba(0,146,63,.12);
        }
        .btn-login {
            width:100%;
            padding:12px;
            margin-top:10px;
            background:var(--primary);
            color:#fff;
            border:none;
            border-radius:8px;
            font-size:13px; font-weight:700; letter-spacing:1.2px;
            font-family:inherit;
            cursor:pointer;
            transition:background .2s, transform .1s, box-shadow .2s;
            box-shadow:0 4px 14px rgba(0,146,63,.25);
        }
        .btn-login:hover { background:var(--primary-hover); box-shadow:0 6px 20px rgba(0,146,63,.35); transform:translateY(-1px); }
        .btn-login:active { transform:translateY(0); box-shadow:0 2px 8px rgba(0,146,63,.2); }

        /* Responsive: stack on smaller screens */
        @media (max-width: 768px) {
            .login-shell {
                grid-template-columns:1fr;
                gap:24px;
                max-width:400px;
            }
            .brand-side img, .brand-fallback { max-width:180px; width:180px; height:180px; }
            .brand-fallback h1 { font-size:26px; }
            .brand-fallback p { font-size:11px; }
            .form-side { padding:28px 22px; }
            .login-title { font-size:17px; margin-bottom:20px; }
        }
    </style>
</head>
<body>
    <div class="login-shell">
        <div class="brand-side">
            <?php
            $logoPath = BASE_PATH . '/public/img/logo.png';
            $logoVer = file_exists($logoPath) ? filemtime($logoPath) : 0;
            ?>
            <img
                src="<?= base_url('img/logo.png') ?>?v=<?= $logoVer ?>"
                alt="Logo SMK NU Muara Sugihan"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="brand-fallback" style="display:none">
                <h1>SMK NU</h1>
                <p>MUARA SUGIHAN</p>
            </div>
        </div>

        <div class="form-side">
            <h2 class="login-title">SISTEM INFORMASI<br>SMK NU MUARA SUGIHAN</h2>

            <?php if(!empty($error)): ?>
                <div class="alert error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= base_url('index.php?page=login') ?>" autocomplete="on">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" name="username" class="form-control" placeholder="Masukkan Username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                </div>
                <button type="submit" class="btn-login">LOGIN</button>
            </form>
        </div>
    </div>
</body>
</html>
