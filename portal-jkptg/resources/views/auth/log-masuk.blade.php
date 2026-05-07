<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<link rel="icon" type="image/png" href="/images/logo-jkptg.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ __('messages.auth.page_title') }} | {{ __('messages.site_name') }}</title>
<meta name="robots" content="noindex,nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
  --primary-900: #0B3220;
  --primary-700: #1B5E3F;
  --primary-500: #4F7E60;
  --primary-pale: #E8F0EA;
  --jata-yellow: #FBBF24;
  --navy:        #0B1B2D;
  --slate:       #5C6A7A;
  --border:      #E5EDE8;
  --off-white:   #F4FBF7;
  --canvas:      #FAF7F0;
  --canvas-ink:  #2A2620;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }
body {
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  -webkit-font-smoothing: antialiased;
  color: var(--canvas-ink);
  min-height: 100vh;
  display: flex;
}

/* ── Left panel ── */
.panel-left {
  position: relative;
  flex: 0 0 42%;
  background: linear-gradient(155deg, #061B12 0%, var(--primary-900) 35%, var(--primary-700) 75%, #2A7A52 100%);
  display: flex; flex-direction: column; justify-content: center;
  padding: 3rem 2.75rem; overflow: hidden;
}
.deco { position: absolute; border-radius: 50%; opacity: .10; pointer-events: none; }
.deco-1 { width: 420px; height: 420px; background: #fff; top: -120px; right: -140px; }
.deco-2 { width: 260px; height: 260px; background: #fff; bottom: 60px; left: -80px; }
.deco-3 { width: 140px; height: 140px; background: var(--jata-yellow); bottom: 180px; right: 40px; opacity: .12; }
.deco-4 { width: 60px;  height: 60px;  background: #BBE3CD; top: 200px; left: 30px; opacity: .25; }
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
  text-transform: uppercase; color: rgba(255,255,255,.78);
}
.portal-dot { width: 6px; height: 6px; background: var(--jata-yellow); border-radius: 50%; flex-shrink: 0; }
.left-title {
  color: white; font-size: 1.875rem; font-weight: 800;
  line-height: 1.15; letter-spacing: -.02em; margin-bottom: 1rem;
}
.left-desc {
  color: rgba(255,255,255,.72);
  font-size: .9375rem; line-height: 1.65;
  margin-bottom: 2.5rem; max-width: 320px;
}
.features { display: flex; flex-direction: column; gap: .75rem; max-width: 360px; }
.feature { display: flex; align-items: flex-start; gap: .75rem; color: rgba(255,255,255,.85); font-size: .875rem; line-height: 1.45; }
.feature-dot { width: 8px; height: 8px; background: var(--jata-yellow); border-radius: 50%; flex-shrink: 0; margin-top: 6px; }
.left-footer {
  position: absolute; bottom: 1.5rem; left: 2.75rem; right: 2.75rem; z-index: 1;
  font-size: .75rem; color: rgba(255,255,255,.40); line-height: 1.4;
}

/* ── Right panel ── */
.panel-right {
  flex: 1; background: var(--off-white);
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  padding: 28px 16px 48px; overflow-y: auto;
  position: relative;
}
.panel-right::before {
  content: '';
  position: fixed; inset: 0;
  background:
    radial-gradient(ellipse 70% 60% at 10% 20%, rgba(11,50,32,.07) 0%, transparent 55%),
    radial-gradient(ellipse 50% 50% at 90% 80%, rgba(27,94,63,.05) 0%, transparent 50%);
  pointer-events: none; z-index: 0;
}
.page-wrap { position: relative; z-index: 1; width: 100%; max-width: 440px; }

/* ── Mobile ── */
@media (max-width: 768px) {
  .panel-left { display: none; }
  body { flex-direction: column; }
}

/* Back link */
.back-link {
  display: inline-flex; align-items: center; gap: 5px;
  font-size: 13px; color: var(--slate); text-decoration: none;
  margin-bottom: 24px; transition: color .15s;
}
.back-link:hover { color: var(--primary-700); }
.back-link svg { transition: transform .15s; }
.back-link:hover svg { transform: translateX(-2px); }

/* Card */
.card {
  background: #ffffff;
  border: 1px solid var(--border);
  border-radius: 10px;
  box-shadow: rgba(11,50,32,0.10) 0px 10px 30px -8px, rgba(0,0,0,0.05) 0px 4px 12px -4px;
  overflow: hidden;
  margin-bottom: 20px;
}
.card-top {
  height: 3px;
  background: linear-gradient(90deg, var(--primary-900) 0%, var(--primary-700) 50%, var(--jata-yellow) 100%);
}
.card-body { padding: 32px 32px 28px; }

/* Logo */
.logo-row { display: flex; align-items: center; gap: 12px; margin-bottom: 18px; }
.logo-row img { height: 48px; width: auto; flex-shrink: 0; }
.logo-text-block { line-height: 1.15; }
.logo-text { font-size: 15px; font-weight: 700; color: var(--primary-900); letter-spacing: -0.2px; }
.logo-sub  { font-size: 11px; color: var(--slate); margin-top: 2px; }

/* Gov badge */
.gov-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: var(--primary-pale);
  border: 1px solid #BBE3CD;
  border-radius: 4px; padding: 4px 9px;
  margin-bottom: 18px;
  font-size: 10.5px; font-weight: 600;
  letter-spacing: .03em;
  color: var(--primary-900);
}
.gov-badge-dot { width: 6px; height: 6px; background: var(--primary-700); border-radius: 50%; }

.card-title { font-size: 19px; font-weight: 700; color: var(--navy); margin-bottom: 6px; letter-spacing: -0.3px; }
.card-sub   { font-size: 13px; color: var(--slate); margin-bottom: 22px; line-height: 1.5; }

/* Errors */
.error-box {
  background: #fef2f2; border: 1px solid #fecaca; border-radius: 6px;
  padding: 10px 14px; margin-bottom: 16px; font-size: 13px; color: #b91c1c;
}
.error-box div + div { margin-top: 4px; }

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
  font-family: 'Inter', sans-serif;
  font-size: 13.5px;
  color: var(--navy);
  background: #fff;
  outline: none;
  transition: border-color 150ms ease, box-shadow 150ms ease;
}
.field-wrap input::placeholder { color: #9aabbc; }
.field-wrap input:focus {
  border-color: var(--primary-700);
  box-shadow: 0 0 0 3px rgba(27,94,63,.12);
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
.remember-label input[type="checkbox"] { width: 15px; height: 15px; accent-color: var(--primary-700); cursor: pointer; }
.forgot-link { font-size: 13px; color: var(--primary-700); text-decoration: none; font-weight: 500; }
.forgot-link:hover { text-decoration: underline; }

/* Submit */
.submit-btn {
  width: 100%;
  padding: 11px;
  background: var(--primary-900);
  color: #fff;
  font-family: 'Inter', sans-serif;
  font-size: 14px;
  font-weight: 600;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  transition: background 150ms ease, transform 150ms ease;
  margin-bottom: 4px;
  letter-spacing: 0.2px;
}
.submit-btn:hover { background: var(--primary-700); transform: translateY(-1px); }
.submit-btn:active { transform: translateY(0); }

/* Card footer */
.card-foot {
  border-top: 1px solid #f3f4f6;
  padding: 16px 32px;
  text-align: center;
  font-size: 13px;
  color: var(--slate);
}
.card-foot a { color: var(--primary-700); font-weight: 600; text-decoration: none; }
.card-foot a:hover { text-decoration: underline; }

/* Demo link */
.demo-link-wrap { text-align: center; margin-bottom: 20px; }
.demo-link {
  background: none; border: none; cursor: pointer;
  font-family: 'Inter', sans-serif;
  font-size: 12.5px; color: var(--slate);
  text-decoration: underline; text-underline-offset: 3px;
  transition: color .15s; padding: 0;
}
.demo-link:hover { color: var(--primary-700); }

/* Lang toggle */
.lang-row { text-align: center; margin-top: 6px; font-size: 12px; }
.lang-row a { color: var(--slate); text-decoration: none; padding: 0 8px; }
.lang-row a.active { color: var(--primary-900); font-weight: 600; }
.lang-row a:hover { color: var(--primary-700); }
.lang-row span { color: #cfd6e0; }

/* ── MODAL ──────────────────────────────────────── */
.modal-backdrop {
  position: fixed; inset: 0;
  background: rgba(11,27,45,0.55);
  display: flex; align-items: center; justify-content: center;
  z-index: 100;
  opacity: 0; pointer-events: none;
  transition: opacity 200ms ease;
}
.modal-backdrop.open { opacity: 1; pointer-events: auto; }
.modal {
  background: #fff; border-radius: 10px;
  width: 92vw; max-width: 560px; max-height: 85vh;
  overflow: hidden;
  display: flex; flex-direction: column;
  box-shadow: rgba(11,27,45,0.28) 0px 24px 64px -8px, rgba(0,0,0,0.12) 0px 8px 20px -4px;
  transform: scale(0.95) translateY(12px);
  transition: transform 200ms ease;
}
.modal-backdrop.open .modal { transform: scale(1) translateY(0); }
.modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 20px; background: var(--primary-900); flex-shrink: 0;
}
.modal-title { font-size: 14px; font-weight: 600; color: #fff; display: flex; align-items: center; gap: 8px; }
.modal-badge {
  font-size: 10px; font-weight: 700;
  padding: 2px 8px; border-radius: 9999px;
  background: rgba(255,255,255,0.18); color: #fff;
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
.demo-table tbody tr { cursor: pointer; }
.demo-table tbody tr:hover td { background: var(--primary-pale); }
.role-pill {
  display: inline-block; font-size: 10.5px; font-weight: 600;
  padding: 2px 8px; border-radius: 4px;
  background: var(--primary-pale); color: var(--primary-900);
  white-space: nowrap;
}
.demo-pw {
  font-family: ui-monospace, monospace;
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
.page-footer {
  text-align: center; font-size: 11.5px;
  color: #9aabbc; margin-top: 16px;
}
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
            {{ __('messages.auth.badge') }}
        </div>
        <h1 class="left-title">{{ __('messages.auth.left_title_1') }}<br>{{ __('messages.auth.left_title_2') }}</h1>
        <p class="left-desc">{{ __('messages.auth.left_desc') }}</p>
        <div class="features">
            <div class="feature"><div class="feature-dot"></div><span>{{ __('messages.auth.features.f1') }}</span></div>
            <div class="feature"><div class="feature-dot"></div><span>{{ __('messages.auth.features.f2') }}</span></div>
            <div class="feature"><div class="feature-dot"></div><span>{{ __('messages.auth.features.f3') }}</span></div>
            <div class="feature"><div class="feature-dot"></div><span>{{ __('messages.auth.features.f4') }}</span></div>
        </div>
    </div>
    <p class="left-footer">{!! __('messages.auth.copyright', ['year' => date('Y')]) !!}</p>
</div>

<div class="panel-right">
<div class="page-wrap">

    <a href="{{ route('home') }}" class="back-link">
        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.5 3L5 8l5.5 5"/>
        </svg>
        {{ __('messages.auth.back') }}
    </a>

    <div class="card">
        <div class="card-top"></div>
        <div class="card-body">

            <div class="logo-row">
                <img src="/images/logo-jkptg.png" alt="JKPTG">
                <div class="logo-text-block">
                    <div class="logo-text">JKPTG</div>
                    <div class="logo-sub">{{ __('messages.site_description') }}</div>
                </div>
            </div>

            <div class="gov-badge">
                <div class="gov-badge-dot"></div>
                {{ __('messages.auth.gov_badge') }}
            </div>

            <div class="card-title">{{ __('messages.auth.card_title') }}</div>
            <div class="card-sub">{{ __('messages.auth.card_sub') }}</div>

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
                    <label for="email">{{ __('messages.auth.email_label') }}</label>
                    <div class="field-wrap">
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               placeholder="{{ __('messages.auth.email_placeholder') }}"
                               autofocus autocomplete="email" required>
                        <span class="field-icon">
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        </span>
                    </div>
                </div>

                <div class="field">
                    <label for="password">{{ __('messages.auth.password_label') }}</label>
                    <div class="field-wrap">
                        <input type="password" id="password" name="password"
                               placeholder="••••••••"
                               autocomplete="current-password" required minlength="6">
                        <span class="field-icon">
                            <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        </span>
                    </div>
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        {{ __('messages.auth.remember') }}
                    </label>
                    <a href="{{ route('hubungi.index') }}" class="forgot-link">{{ __('messages.auth.forgot') }}</a>
                </div>

                <button type="submit" class="submit-btn">{{ __('messages.auth.submit') }}</button>
            </form>
        </div>

        <div class="card-foot">
            {{ __('messages.auth.no_account') }} <a href="{{ route('hubungi.index') }}">{{ __('messages.auth.contact_admin') }}</a>
        </div>
    </div>

    @if(config('app.debug'))
        <div class="demo-link-wrap">
            <button type="button" class="demo-link" onclick="openDemo()">{{ __('messages.auth.demo_link') }}</button>
        </div>
    @endif

    <div class="lang-row">
        <a href="{{ route('locale.switch', 'ms') }}" class="{{ app()->getLocale() === 'ms' ? 'active' : '' }}">BM</a>
        <span aria-hidden="true">|</span>
        <a href="{{ route('locale.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
    </div>

    <p class="page-footer">{!! __('messages.auth.copyright', ['year' => date('Y')]) !!}</p>

</div>
</div>{{-- end panel-right --}}

@if(config('app.debug'))
<div class="modal-backdrop" id="demoBackdrop" onclick="handleBackdropClick(event)" role="dialog" aria-modal="true" aria-labelledby="demoModalTitle">
    <div class="modal" id="demoModal">
        <div class="modal-head">
            <div class="modal-title" id="demoModalTitle">
                <svg width="15" height="15" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                {{ __('messages.auth.demo_modal_title') }}
                <span class="modal-badge">{{ __('messages.auth.demo_modal_badge') }}</span>
            </div>
            <button class="modal-close" type="button" onclick="closeDemo()" aria-label="Close">&times;</button>
        </div>
        <div class="modal-body">
            <table class="demo-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.auth.demo_col_role') }}</th>
                        <th>{{ __('messages.auth.demo_col_email') }}</th>
                        <th>{{ __('messages.auth.demo_col_pw') }}</th>
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
        <div class="modal-foot">{{ __('messages.auth.demo_modal_foot') }}</div>
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
    document.querySelector('.submit-btn').focus();
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDemo(); });
</script>
@endif
</body>
</html>
