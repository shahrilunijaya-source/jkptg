<!doctype html>
<html lang="{{ app()->getLocale() }}" x-data="{ a11y: { large: false, xlarge: false, contrast: false } }"
      :class="{ 'a11y-large-text': a11y.large, 'a11y-extra-large-text': a11y.xlarge, 'a11y-high-contrast': a11y.contrast }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="agency" content="JKPTG">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
      $siteTitle = \App\Models\Setting::get('site.title');
      $siteTitle = is_array($siteTitle) ? ($siteTitle[app()->getLocale()] ?? __('messages.site_name')) : ($siteTitle ?? __('messages.site_name'));
    @endphp
    <title>@yield('title', $siteTitle)</title>
    <meta name="description" content="@yield('description', __('messages.site_description'))">
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
</body>
</html>
