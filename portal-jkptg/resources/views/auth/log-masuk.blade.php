<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="/images/logo-jkptg.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="robots" content="noindex,nofollow">
<title>Log Masuk — Portal JKPTG</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --accent:      #006837;
  --accent-dark: #004d28;
  --accent-bg:   #f0fdf4;
  --accent-mid:  #16a34a;
  --navy:        #061B31;
  --slate:       #64748D;
  --border:      #E5EDF5;
  --off-white:   #F8FAFC;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }
body {
  font-family: 'Poppins', sans-serif;
  -webkit-font-smoothing: antialiased;
  color: var(--navy);
  min-height: 100vh;
  display: flex;
}

/* ── Left panel ── */
.panel-left {
  position: relative;
  flex: 0 0 42%;
  background: linear-gradient(155deg, #003d20 0%, #006837 45%, #008545 100%);
  display: flex; flex-direction: column; justify-content: center;
  padding: 3rem 2.75rem; overflow: hidden;
}
.deco { position: absolute; border-radius: 50%; opacity: .10; pointer-events: none; }
.deco-1 { width: 420px; height: 420px; background: #fff; top: -120px; right: -140px; }
.deco-2 { width: 260px; height: 260px; background: #fff; bottom: 60px; left: -80px; }
.deco-3 { width: 140px; height: 140px; background: #86efac; bottom: 180px; right: 40px; opacity: .15; }
.deco-4 { width: 60px;  height: 60px;  background: #bbf7d0; top: 200px; left: 30px; opacity: .20; }
.dot-grid {
  position: absolute; inset: 0; pointer-events: none;
  background-image: radial-gradient(circle, rgba(255,255,255,.10) 1px, transparent 1px);
  background-size: 28px 28px;
}
.left-inner { position: relative; z-index: 1; }
.portal-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,.10); border: 1px solid rgba(255,255,255,.20);
  border-radius: 4px; padding: 5px 10px; margin-bottom: 1.5rem;
  font-size: .6875rem; font-weight: 600; letter-spacing: .06em;
  text-transform: uppercase; color: rgba(255,255,255,.75);
}
.portal-dot { width: 6px; height: 6px; background: #FFCC00; border-radius: 50%; flex-shrink: 0; }
.left-title { color: white; font-size: 1.875rem; font-weight: 800; line-height: 1.25; letter-spacing: -.02em; margin-bottom: 1rem; }
.left-desc { color: rgba(255,255,255,.65); font-size: .9375rem; line-height: 1.6; margin-bottom: 2.5rem; max-width: 280px; }
.features { display: flex; flex-direction: column; gap: .75rem; }
.feature { display: flex; align-items: center; gap: .75rem; color: rgba(255,255,255,.80); font-size: .875rem; }
.feature-dot { width: 8px; height: 8px; background: #FFCC00; border-radius: 50%; flex-shrink: 0; }
.left-footer { position: absolute; bottom: 1.5rem; left: 2.75rem; right: 2.75rem; z-index: 1; font-size: .75rem; color: rgba(255,255,255,.35); }

/* ── Right panel ── */
.panel-right {
  flex: 1; background: #f4fbf7;
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 28px 16px 48px; overflow-y: auto;
}
.panel-right::before {
  content: '';
  position: fixed; inset: 0;
  background:
    radial-gradient(ellipse 70% 60% at 10% 20%, rgba(0,104,55,.07) 0%, transparent 55%),
    radial-gradient(ellipse 50% 50% at 90% 80%, rgba(0,104,55,.05) 0%, transparent 50%);
  pointer-events: none; z-index: 0;
}

.page-wrap {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 440px;
}

/* ── Mobile ── */
@media (max-width: 768px) {
  .panel-left { display: none; }
  body { flex-direction: column; }
}

/* Back link */
.back-link {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  font-size: 13px;
  color: var(--slate);
  text-decoration: none;
  margin-bottom: 24px;
  transition: color .15s;
}
.back-link:hover { color: var(--accent); }
.back-link svg { transition: transform .15s; }
.back-link:hover svg { transform: translateX(-2px); }

/* Card */
.card {
  background: #ffffff;
  border: 1px solid var(--border);
  border-radius: 10px;
  box-shadow: rgba(50,50,93,0.12) 0px 10px 30px -8px, rgba(0,0,0,0.06) 0px 4px 12px -4px;
  overflow: hidden;
  margin-bottom: 20px;
}
.card-top {
  height: 3px;
  background: linear-gradient(90deg, var(--accent) 0%, var(--accent-mid) 100%);
}
.card-body { padding: 32px 32px 28px; }

/* Logo */
.logo-row {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 24px;
}
.logo-row img { height: 44px; width: auto; flex-shrink: 0; }

.card-title { font-size: 19px; font-weight: 600; color: var(--navy); margin-bottom: 6px; letter-spacing: -0.3px; }
.card-sub   { font-size: 13px; color: var(--slate); margin-bottom: 24px; }

/* Errors */
.error-box {
  background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px;
  padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: #b91c1c;
}

/* Fields */
.field { margin-bottom: 16px; }
.field label {
  display: block; font-size: 12px; font-weight: 500;
  color: var(--navy); margin-bottom: 6px; letter-spacing: 0.2px;
}
.field-wrap { position: relative; }
.field-wrap input {
  width: 100%;
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 10px 38px 10px 14px;
  font-family: 'Poppins', sans-serif;
  font-size: 13.5px;
  color: var(--navy);
  background: #fff;
  outline: none;
  transition: border-color 150ms ease, box-shadow 150ms ease;
}
.field-wrap input::placeholder { color: #9aabbc; }
.field-wrap input:focus {
  border-color: var(--accent);
  box-shadow: 0 0 0 3px rgba(0,104,55,.12);
}
.field-icon {
  position: absolute; right: 12px; top: 50%;
  transform: translateY(-50%);
  color: #9aabbc; pointer-events: none;
}

.remember-row {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px;
}
.remember-label {
  display: flex; align-items: center; gap: 7px;
  font-size: 13px; color: var(--slate); cursor: pointer;
}
.remember-label input[type="checkbox"] { width: 15px; height: 15px; accent-color: var(--accent); cursor: pointer; }
.forgot-link { font-size: 13px; color: var(--accent); text-decoration: none; }
.forgot-link:hover { text-decoration: underline; }

/* Submit */
.submit-btn {
  width: 100%;
  padding: 11px;
  background: var(--accent);
  color: #fff;
  font-family: 'Poppins', sans-serif;
  font-size: 14px;
  font-weight: 500;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 150ms ease, transform 150ms ease;
  margin-bottom: 20px;
}
.submit-btn:hover { background: var(--accent-dark); transform: translateY(-1px); }
.submit-btn:active { transform: translateY(0); }

/* Card footer */
.card-foot {
  border-top: 1px solid #f3f4f6;
  padding: 16px 32px;
  text-align: center;
  font-size: 13px;
  color: var(--slate);
}
.card-foot a { color: var(--accent); font-weight: 600; text-decoration: none; }
.card-foot a:hover { text-decoration: underline; }

/* Demo link */
.demo-link-wrap { text-align: center; margin-bottom: 20px; }
.demo-link {
  background: none; border: none; cursor: pointer;
  font-family: 'Poppins', sans-serif;
  font-size: 12.5px; color: var(--slate);
  text-decoration: underline; text-underline-offset: 3px;
  transition: color .15s; padding: 0;
}
.demo-link:hover { color: var(--accent); }

/* Footer */
.page-footer { text-align: center; font-size: 12px; color: #9aabbc; margin-top: auto; padding-top: 16px; }

/* ── MODAL ──────────────────────────────────────── */
.modal-backdrop {
  position: fixed; inset: 0;
  background: rgba(6,27,49,0.55);
  display: flex; align-items: center; justify-content: center;
  z-index: 100;
  opacity: 0; pointer-events: none;
  transition: opacity 200ms ease;
}
.modal-backdrop.open { opacity: 1; pointer-events: auto; }

.modal {
  background: #fff;
  border-radius: 10px;
  width: 92vw; max-width: 560px;
  max-height: 85vh;
  overflow: hidden;
  display: flex; flex-direction: column;
  box-shadow: rgba(6,27,49,0.28) 0px 24px 64px -8px, rgba(0,0,0,0.12) 0px 8px 20px -4px;
  transform: scale(0.95) translateY(12px);
  transition: transform 200ms ease;
}
.modal-backdrop.open .modal { transform: scale(1) translateY(0); }

.modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 20px;
  background: var(--accent);
  flex-shrink: 0;
}
.modal-title { font-size: 14px; font-weight: 600; color: #fff; display: flex; align-items: center; gap: 8px; }
.modal-badge {
  font-size: 10px; font-weight: 700;
  padding: 2px 8px; border-radius: 9999px;
  background: rgba(255,255,255,0.2); color: #fff;
}
.modal-close {
  width: 28px; height: 28px;
  border-radius: 6px; border: none;
  background: rgba(255,255,255,0.15);
  color: #fff; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  font-size: 15px; transition: background .15s;
}
.modal-close:hover { background: rgba(255,255,255,0.25); }

.modal-body { overflow-y: auto; }

.demo-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.demo-table th {
  padding: 8px 16px;
  font-size: 10px; font-weight: 700; color: #9aabbc;
  text-transform: uppercase; letter-spacing: .06em;
  background: #f9fafb; border-bottom: 1px solid var(--border);
  text-align: left; position: sticky; top: 0;
}
.demo-table td {
  padding: 10px 16px;
  border-bottom: 1px solid #f3f4f6;
  color: var(--navy);
  vertical-align: middle;
}
.demo-table tr:last-child td { border-bottom: none; }
.demo-table tr:hover td { background: #fafafa; }
.demo-table tbody tr { cursor: pointer; }
.demo-table tbody tr:hover td { background: var(--accent-bg); }

.role-pill {
  display: inline-block; font-size: 10.5px; font-weight: 500;
  padding: 2px 8px; border-radius: 4px;
  background: var(--accent-bg); color: var(--accent);
  white-space: nowrap;
}
.demo-pw {
  font-family: 'SF Mono', 'Cascadia Code', ui-monospace, monospace;
  font-size: 11.5px; background: #f3f4f6;
  padding: 2px 7px; border-radius: 4px;
  color: var(--navy); white-space: nowrap;
}
.modal-foot {
  padding: 12px 16px;
  background: #f9fafb; border-top: 1px solid var(--border);
  font-size: 12px; color: var(--slate); text-align: center;
  flex-shrink: 0;
}
.modal-foot a { color: var(--accent); font-weight: 500; text-decoration: none; }
.modal-foot a:hover { text-decoration: underline; }
</style>
</head>
<body>

<div class="panel-left">
    <div class="deco deco-1"></div>
    <div class="deco deco-2"></div>
    <div class="deco deco-3"></div>
    <div class="deco deco-4"></div>
    <div class="dot-grid"></div>
    <div class="left-inner">
        <div class="portal-badge">
            <div class="portal-dot"></div>
            Portal Rasmi
        </div>
        <h1 class="left-title">Portal<br>JKPTG</h1>
        <p class="left-desc">Sistem akses kakitangan dan pengguna berdaftar Jabatan Ketua Pengarah Tanah dan Galian Persekutuan.</p>
        <div class="features">
            <div class="feature"><div class="feature-dot"></div><span>Pengurusan kandungan portal jabatan</span></div>
            <div class="feature"><div class="feature-dot"></div><span>Semak status permohonan dan e-perkhidmatan</span></div>
            <div class="feature"><div class="feature-dot"></div><span>Akses borang dan dokumen rasmi jabatan</span></div>
            <div class="feature"><div class="feature-dot"></div><span>Jejak audit penuh untuk pegawai</span></div>
        </div>
    </div>
    <p class="left-footer">Portal JKPTG &copy; {{ date('Y') }} Jabatan Ketua Pengarah Tanah dan Galian Persekutuan</p>
</div>

<div class="panel-right">
<div class="page-wrap">

    {{-- Back to home --}}
    <a href="{{ route('home') }}" class="back-link">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.5 3L5 8l5.5 5"/>
        </svg>
        Kembali ke Laman Utama
    </a>

    {{-- Login card --}}
    <div class="card">
        <div class="card-top"></div>
        <div class="card-body">

            {{-- Logo --}}
            <div class="logo-row">
                <img src="/images/logo-jkptg.png" alt="JKPTG">
            </div>

            <div class="card-title">Log Masuk</div>
            <div class="card-sub">Masukkan kelayakan akaun JKPTG anda untuk meneruskan.</div>

            @if ($errors->any())
                <div class="error-box" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('log-masuk.store') }}" novalidate>
                @csrf

                <div class="field">
                    <label for="email">E-mel</label>
                    <div class="field-wrap">
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@jkptg.gov.my"
                               autofocus autocomplete="email">
                        <span class="field-icon">
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label for="password">Kata Laluan</label>
                    <div class="field-wrap">
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               autocomplete="current-password">
                        <span class="field-icon">
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        </span>
                    </div>
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                    <a href="{{ route('hubungi.index') }}" class="forgot-link">Lupa Kata Laluan?</a>
                </div>

                <button type="submit" class="submit-btn">Log Masuk</button>
            </form>
        </div>

        <div class="card-foot">
            Tiada akaun? <a href="{{ route('hubungi.index') }}">Hubungi pentadbir sistem</a>
        </div>
    </div>

    {{-- Demo accounts link --}}
    @if(config('app.debug'))
    <div class="demo-link-wrap">
        <button class="demo-link" onclick="openDemo()">Lihat Akaun Demo →</button>
    </div>
    @endif

</div>
</div>{{-- end panel-right --}}

@if(config('app.debug'))
{{-- Demo Modal --}}
<div class="modal-backdrop" id="demoBackdrop" onclick="handleBackdropClick(event)">
    <div class="modal" id="demoModal">
        <div class="modal-head">
            <div class="modal-title">
                <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                Akaun Demo
                <span class="modal-badge">Persekitaran Ujian</span>
            </div>
            <button class="modal-close" onclick="closeDemo()">✕</button>
        </div>
        <div class="modal-body">
            <table class="demo-table">
                <thead>
                    <tr>
                        <th>Peranan</th>
                        <th>E-mel</th>
                        <th>Kata Laluan</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $demoAccounts = [
                            ['Super Admin', 'admin@jkptg.demo'],
                            ['Editor',      'editor@jkptg.demo'],
                            ['Viewer',      'viewer@jkptg.demo'],
                            ['Citizen',     'citizen@jkptg.demo'],
                        ];
                    @endphp
                    @foreach ($demoAccounts as [$role, $email])
                        <tr onclick="fillCredentials('{{ $email }}')">
                            <td><span class="role-pill">{{ $role }}</span></td>
                            <td style="font-size:12.5px;">{{ $email }}</td>
                            <td><span class="demo-pw">password</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-foot">
            Klik baris untuk isi borang secara automatik
        </div>
    </div>
</div>

<script>
function openDemo() {
    document.getElementById('demoBackdrop').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeDemo() {
    document.getElementById('demoBackdrop').classList.remove('open');
    document.body.style.overflow = '';
}
function handleBackdropClick(e) {
    if (e.target === document.getElementById('demoBackdrop')) closeDemo();
}
function fillCredentials(email) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = 'password';
    closeDemo();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDemo(); });
</script>
@endif
</body>
</html>
