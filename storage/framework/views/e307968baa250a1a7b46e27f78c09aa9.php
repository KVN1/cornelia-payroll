<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Cornelia Street Bistro</title>
    <link rel="icon" type="image/jpeg" href="<?php echo e(asset('images/logo.jpg')); ?>">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: stretch;
            overflow: hidden;
background: url('<?php echo e(asset("images/bg.jpg")); ?>') left center / cover no-repeat fixed;            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background: linear-gradient(to right, rgba(10,6,3,0.35) 0%, rgba(10,6,3,0.15) 60%);
            z-index: 0;
            pointer-events: none;
        }

        /* ── Left panel ── */
        .left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 48px;
            position: relative;
            z-index: 2;
            overflow: hidden;
        }
        .left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(10,6,3,0.0) 0%,
                rgba(10,6,3,0.35) 55%,
                rgba(10,6,3,0.75) 100%
            );
        }
        .left-content { position: relative; z-index: 1; animation: fadeUp 0.9s ease both; }

        .tag {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(200,132,74,0.18);
            border: 1px solid rgba(200,132,74,0.4);
            backdrop-filter: blur(8px);
            color: #D4A97A;
            font-size: 11px; font-weight: 600;
            letter-spacing: 1.8px; text-transform: uppercase;
            padding: 6px 16px; border-radius: 20px; margin-bottom: 20px;
        }
        .tag-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #C8844A;
            animation: pulse 2s infinite;
        }
        .left-title {
            font-size: 46px; font-weight: 800; color: #fff;
            line-height: 1.12; letter-spacing: -1.2px;
            margin-bottom: 14px;
            text-shadow: 0 2px 24px rgba(0,0,0,0.4);
        }
        .left-title span { color: #D4A97A; }
        .left-desc {
            font-size: 14.5px; color: rgba(255,255,255,0.48);
            line-height: 1.7; max-width: 380px;
        }
        .left-dots { display: flex; gap: 8px; margin-top: 30px; }
        .left-dots span {
            width: 7px; height: 7px; border-radius: 50%;
            background: rgba(255,255,255,0.18);
        }
        .left-dots span:first-child {
            background: #C8844A; width: 24px; border-radius: 4px;
        }

        /* ── Right panel — glassmorphism ── */
        .right {
            width: 440px; flex-shrink: 0;
            background: rgba(10,6,3,0.45);
            backdrop-filter: blur(28px);
            -webkit-backdrop-filter: blur(28px);
            border-left: 1px solid rgba(255,255,255,0.12);
            box-shadow: inset 1px 0 0 rgba(255,255,255,0.07), -12px 0 40px rgba(0,0,0,0.3);
            display: flex; flex-direction: column; justify-content: center;
            padding: 48px 44px;
            position: relative;
            z-index: 2;
            overflow: hidden;
        }
        .right::before {
            content: ''; position: absolute; top: -100px; right: -100px;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(200,132,74,0.10) 0%, transparent 70%);
            border-radius: 50%;
        }
        .right::after {
            content: ''; position: absolute; bottom: -80px; left: -80px;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(139,69,19,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .form-wrap { position: relative; z-index: 1; animation: fadeUp 0.6s 0.15s ease both; }

        /* ── Logo row ── */
        .logo-row { display: flex; align-items: center; gap: 13px; margin-bottom: 36px; }
        .logo-img {
            width: 46px; height: 46px;
            border-radius: 12px; object-fit: cover;
            border: 1.5px solid rgba(255,255,255,0.22);
            box-shadow: 0 4px 16px rgba(0,0,0,0.25);
        }
        .logo-fb {
            width: 46px; height: 46px; border-radius: 12px;
            background: rgba(200,132,74,0.25);
            border: 1.5px solid rgba(200,132,74,0.4);
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }
        .logo-name { font-size: 14px; font-weight: 700; color: #fff; line-height: 1.3; }
        .logo-sub { font-size: 10.5px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1.2px; margin-top: 2px; }

        /* ── Headings ── */
        .form-heading {
            font-size: 27px; font-weight: 800; color: #fff;
            letter-spacing: -0.5px; margin-bottom: 5px;
        }
        .form-sub { font-size: 13.5px; color: rgba(255,255,255,0.45); margin-bottom: 30px; }

        /* ── Form ── */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block; font-size: 10.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            color: rgba(255,255,255,0.55); margin-bottom: 7px;
        }
        .input-wrap { position: relative; }

        /* SVG icons instead of emoji */
        .input-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            opacity: 0.45; pointer-events: none;
            color: #D4A97A;
        }

        .form-control {
            width: 100%; padding: 12px 14px 12px 40px;
            background: rgba(255,255,255,0.09);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.16);
            border-radius: 11px; font-size: 14px;
            font-family: inherit; color: #fff;
            transition: all 0.2s; outline: none;
        }
        .form-control::placeholder { color: rgba(255,255,255,0.28); }
        .form-control:hover {
            background: rgba(255,255,255,0.13);
            border-color: rgba(255,255,255,0.26);
        }
        .form-control:focus {
            background: rgba(255,255,255,0.15);
            border-color: rgba(200,132,74,0.75);
            box-shadow: 0 0 0 3px rgba(200,132,74,0.18);
        }

        /* ── Remember row ── */
        .remember-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 22px;
        }
        .remember-label {
            display: flex; align-items: center; gap: 8px;
            font-size: 13px; color: rgba(255,255,255,0.5); cursor: pointer;
        }
        .remember-label input { width: 15px; height: 15px; accent-color: #C8844A; }
        .show-pw {
            font-size: 12px; color: #D4A97A;
            font-weight: 600; cursor: pointer;
            transition: color 0.15s;
        }
        .show-pw:hover { color: #C8844A; }

        /* ── Button ── */
        .btn-login {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, rgba(200,132,74,0.85), rgba(139,69,19,0.85));
            backdrop-filter: blur(8px);
            color: #fff; border: 1px solid rgba(200,132,74,0.45);
            border-radius: 11px; font-size: 14px; font-weight: 700;
            font-family: inherit; cursor: pointer; transition: all 0.2s;
            box-shadow: 0 4px 20px rgba(200,132,74,0.25);
            letter-spacing: 0.3px;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(200,132,74,0.4);
            background: linear-gradient(135deg, rgba(220,152,94,0.92), rgba(159,89,39,0.92));
        }
        .btn-login:active { transform: scale(0.98); }

        /* ── Alerts ── */
        .alert-error {
            background: rgba(193,18,31,0.18);
            backdrop-filter: blur(8px);
            color: #fca5a5;
            border: 1px solid rgba(193,18,31,0.35);
            border-radius: 10px; padding: 11px 14px;
            font-size: 13px; margin-bottom: 18px;
            display: flex; align-items: center; gap: 8px;
            animation: shake 0.4s ease;
        }
        .alert-success {
            background: rgba(45,106,79,0.18);
            backdrop-filter: blur(8px);
            color: #a7f3d0;
            border: 1px solid rgba(45,106,79,0.35);
            border-radius: 10px; padding: 11px 14px;
            font-size: 13px; margin-bottom: 18px;
            display: flex; align-items: center; gap: 8px;
        }

        /* ── Divider ── */
        .divider { display: flex; align-items: center; gap: 12px; margin: 22px 0 16px; }
        .divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.10); }
        .divider-text { font-size: 10.5px; color: rgba(255,255,255,0.28); font-weight: 500; }

        /* ── Access cards ── */
        .access-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
        .access-card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.10);
            border-radius: 10px; padding: 10px 13px;
            transition: background 0.15s;
        }
        .access-card:hover { background: rgba(255,255,255,0.10); }
        .access-card-title {
            font-size: 12px; font-weight: 700;
            color: rgba(255,255,255,0.8);
            margin-bottom: 2px;
            display: flex; align-items: center; gap: 6px;
        }
        .access-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
        .access-card-sub { font-size: 11px; color: rgba(255,255,255,0.32); }

        .form-footer {
            text-align: center; font-size: 11.5px;
            color: rgba(255,255,255,0.22); margin-top: 24px;
        }

        /* ── Animations ── */
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(22px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes shake {
            0%,100%{transform:translateX(0)}
            20%{transform:translateX(-6px)} 40%{transform:translateX(6px)}
            60%{transform:translateX(-4px)} 80%{transform:translateX(4px)}
        }
        @keyframes pulse {
            0%,100%{opacity:1;transform:scale(1)}
            50%{opacity:0.4;transform:scale(0.8)}
        }

        /* ── Orbs ── */
        .orb {
            position: fixed; border-radius: 50%;
            filter: blur(45px); opacity: 0.55;
            animation: orbFloat ease-in-out infinite;
            pointer-events: none; z-index: 0;
        }
        .orb-1 { width:600px; height:600px; background:#C8844A; top:-180px; left:-120px; animation-duration:10s; }
        .orb-2 { width:450px; height:450px; background:#8B4513; bottom:-120px; right:460px; animation-duration:13s; animation-delay:-4s; }
        .orb-3 { width:350px; height:350px; background:#D4A97A; top:30%; left:15%; animation-duration:9s; animation-delay:-7s; }
        @keyframes orbFloat {
            0%,100%{transform:translate(0,0) scale(1)} 33%{transform:translate(18px,-18px) scale(1.04)} 66%{transform:translate(-12px,14px) scale(0.97)}
        }

        /* ── Particles ── */
        .particles { position:fixed; inset:0; z-index:0; pointer-events:none; overflow:hidden; }
        .particle {
            position: absolute; bottom: -10px; border-radius: 50%;
            animation: floatUp linear infinite; pointer-events: none;
        }
        @keyframes floatUp {
            0%  { transform:translateY(0) scale(1); opacity:0; }
            8%  { opacity:1; }
            90% { opacity:1; }
            100%{ transform:translateY(-100vh) scale(0.2); opacity:0; }
        }

        @media (max-width: 768px) {
            .left { display: none; }
            .right {
                width: 100%; padding: 40px 28px;
                background: rgba(26,18,8,0.85);
                backdrop-filter: blur(20px);
                border-left: none;
            }
        }
    </style>
</head>
<body>

    
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    
    <div class="particles" id="particles"></div>

    
    <div class="left">
        <div class="left-content">
            <div class="tag"><span class="tag-dot"></span> Payroll System</div>
            <div class="left-title">Cornelia Street <br> <span>Bistro House</span></div>
            <div class="left-desc">Manage your team's attendance, payroll, and leaves — all in one place.</div>
            <div class="left-dots"><span></span><span></span><span></span><span></span></div>
        </div>
    </div>

    
    <div class="right">
        <div class="form-wrap">

            
            <div class="logo-row">
                <img src="<?php echo e(asset('images/logo.jpg')); ?>" class="logo-img"
                     onerror="this.style.display='none';document.getElementById('logo-fb').style.display='flex'">
                <div class="logo-fb" id="logo-fb" style="display:none">☕</div>
                <div>
                    <div class="logo-name">Cornelia Street Bistro</div>
                    <div class="logo-sub">Payroll Portal</div>
                </div>
            </div>

            <div class="form-heading">Welcome back</div>
            <div class="form-sub">Sign in with your username to continue</div>

            <?php if($errors->any()): ?>
            <div class="alert-error">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <?php echo e($errors->first()); ?>

            </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
            <div class="alert-success">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <?php echo e(session('success')); ?>

            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.post')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <input type="text" name="username" class="form-control"
                               value="<?php echo e(old('username')); ?>"
                               placeholder="your_username" required autofocus autocomplete="username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input type="password" name="password" id="password" class="form-control"
                               placeholder="••••••••" required autocomplete="current-password">
                    </div>
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    <span class="show-pw" onclick="togglePw()">Show password</span>
                </div>

                <button type="submit" class="btn-login">Sign In →</button>
            </form>

            <div class="divider">
                <div class="divider-line"></div>
                <div class="divider-text">Access Levels</div>
                <div class="divider-line"></div>
            </div>

            <div class="access-row">
                <div class="access-card">
                    <div class="access-card-title">
                        <span class="access-dot" style="background:#D4A97A"></span> Admin / HR
                    </div>
                    <div class="access-card-sub">Full system access</div>
                </div>
                <div class="access-card">
                    <div class="access-card-title">
                        <span class="access-dot" style="background:rgba(255,255,255,0.35)"></span> Staff
                    </div>
                    <div class="access-card-sub">Attendance only</div>
                </div>
            </div>

            <div class="form-footer">© <?php echo e(date('Y')); ?> Cornelia Street Bistro</div>
        </div>
    </div>

<script>
function togglePw() {
    const i = document.getElementById('password');
    const b = event.target;
    i.type = i.type === 'password' ? 'text' : 'password';
    b.textContent = i.type === 'password' ? 'Show password' : 'Hide password';
}

// Floating particles
(function() {
    const container = document.getElementById('particles');
    const colors = [
        'rgba(200,132,74,1)',
        'rgba(212,169,122,1)',
        'rgba(255,180,60,1)',
        'rgba(230,150,50,1)',
        'rgba(255,220,130,1)',
        'rgba(255,160,40,1)',
    ];
    for (let i = 0; i < 70; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        const size = Math.random() * 8 + 2;
        p.style.left              = Math.random() * 100 + 'vw';
        p.style.width             = size + 'px';
        p.style.height            = size + 'px';
        p.style.background        = colors[Math.floor(Math.random() * colors.length)];
        p.style.animationDuration = (Math.random() * 8 + 5) + 's';
        p.style.animationDelay    = (Math.random() * 10) + 's';
        p.style.boxShadow         = `0 0 ${size * 3}px rgba(255,160,60,1), 0 0 ${size * 6}px rgba(200,132,74,0.6), 0 0 ${size * 10}px rgba(200,132,74,0.3)`;
        container.appendChild(p);
    }
})();
</script>
</body>
</html><?php /**PATH D:\xampp\htdocs\Cornelia\resources\views/auth/login.blade.php ENDPATH**/ ?>