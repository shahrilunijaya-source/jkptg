<!doctype html>
<html lang="{{ app()->getLocale() }}" x-data="{ a11y: { large: false, xlarge: false, contrast: false } }"
      :class="{ 'a11y-large-text': a11y.large, 'a11y-extra-large-text': a11y.xlarge, 'a11y-high-contrast': a11y.contrast }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
      $siteTitle = \App\Models\Setting::get('site.title');
      $siteTitle = is_array($siteTitle) ? ($siteTitle[app()->getLocale()] ?? __('messages.site_name')) : ($siteTitle ?? __('messages.site_name'));
    @endphp
    <title>@yield('title', $siteTitle)</title>
    <meta name="description" content="@yield('description', __('messages.site_description'))">

    {{-- SPLaSK / govt portal audit tags (Sistem Pemantauan Laman Web Sektor Awam) --}}
    <meta name="agency" content="JKPTG">
    <meta name="agency-full" content="Jabatan Ketua Pengarah Tanah dan Galian Persekutuan">
    <meta name="ministry" content="KPKT">
    <meta name="splask-portal-version" content="2026">
    <meta name="splask-w3c-aa" content="WCAG 2.1 AA">
    <meta name="splask-language" content="ms,en">
    <meta name="splask-mobile" content="responsive">
    <meta name="splask-last-updated" content="{{ \App\Models\Setting::get('site.last_updated', now()->toIso8601String()) }}">
    <meta name="splask-visitor-counter" content="{{ \App\Models\Setting::get('site.visitor_count', 0) }}">
    <meta name="splask-feedback-url" content="{{ url('/hubungi') }}">
    <meta name="splask-search" content="{{ url('/cari') }}">
    <meta name="splask-sitemap" content="{{ url('/sitemap.xml') }}">
    <meta name="splask-security" content="{{ url('/.well-known/security.txt') }}">
    <meta name="splask-privacy" content="{{ url('/halaman/polisi-privasi') }}">

    {{-- Open Graph + Twitter Card --}}
    <meta property="og:title" content="@yield('title', $siteTitle)">
    <meta property="og:description" content="@yield('description', __('messages.site_description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'ms' ? 'ms_MY' : 'en_MY' }}">
    <meta name="twitter:card" content="summary">

    {{-- Canonical + alternate hreflang --}}
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="ms" href="{{ route('locale.switch', 'ms') }}">
    <link rel="alternate" hreflang="en" href="{{ route('locale.switch', 'en') }}">
    <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    @livewireStyles
</head>
<body>
    <a href="#content" class="skip-link">{{ __('messages.skip_to_content') }}</a>

    @include('partials.accessibility-panel')
    @include('partials.utility-bar')
    @include('partials.header')

    <main id="content">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    @include('partials.footer')
    @livewire('public.chatbot.bubble')

    @livewireScripts
    @stack('scripts')
    <script>
    (function() {
      var io = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
          if (e.isIntersecting) { e.target.classList.add('revealed'); io.unobserve(e.target); }
        });
      }, { threshold: 0.08, rootMargin: '0px 0px -28px 0px' });
      document.querySelectorAll('.reveal-on-scroll').forEach(function(el) { io.observe(el); });
    })();
    </script>
</body>
</html>
