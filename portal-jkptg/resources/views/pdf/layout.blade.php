<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <style>
        @page { margin: 22mm 18mm; }
        body { font-family: DejaVu Sans, sans-serif; color: #1f2937; font-size: 11px; line-height: 1.55; }
        h1 { color: #243D57; font-size: 22px; margin: 0 0 6px; }
        h2 { color: #243D57; font-size: 15px; margin: 18px 0 6px; padding-bottom: 4px; border-bottom: 1px solid #e5e7eb; }
        h3 { color: #243D57; font-size: 12px; margin: 10px 0 4px; }
        .meta { color: #6b7280; font-size: 10px; margin-bottom: 18px; padding-bottom: 10px; border-bottom: 2px solid #243D57; }
        .footer { position: fixed; bottom: -10mm; left: 0; right: 0; border-top: 1px solid #e5e7eb; padding-top: 6px; font-size: 9px; color: #6b7280; text-align: center; }
        .step { background: #f9fafb; border: 1px solid #e5e7eb; border-left: 3px solid #243D57; padding: 8px 12px; margin: 4px 0; border-radius: 4px; }
        .step-num { display: inline-block; width: 22px; height: 22px; background: #243D57; color: white; border-radius: 50%; text-align: center; font-weight: bold; line-height: 22px; margin-right: 8px; }
        .arrow { text-align: center; color: #243D57; font-size: 16px; margin: 4px 0; }
        .badge { display: inline-block; background: #FCC203; color: #243D57; padding: 6px 14px; border-radius: 4px; font-weight: bold; }
        .badge-end { background: #243D57; color: white; }
        ul, ol { padding-left: 18px; }
        p { margin: 6px 0; }
        .small { font-size: 9px; color: #6b7280; }
        .disclaimer { background: #fef9c3; border: 1px solid #facc15; padding: 10px 14px; border-radius: 4px; margin-top: 18px; font-size: 10px; }
    </style>
</head>
<body>
    <h1>@yield('heading')</h1>
    <div class="meta">
        @yield('meta')
        @hasSection('meta')<br>@endif
        Portal Rasmi JKPTG · jkptg.gov.my · {{ now()->isoFormat('D MMM Y') }}
    </div>
    @yield('content')
    <div class="footer">{{ __('messages.site_name') }} · jkptg.gov.my</div>
</body>
</html>
