@php
  $address = \App\Models\Setting::get('site.address');
  $address = is_array($address) ? ($address[app()->getLocale()] ?? '') : $address;
  $phone = \App\Models\Setting::get('site.phone', '03-8000 8000');
  $email = \App\Models\Setting::get('site.email', 'webmaster@jkptg.gov.my');
  $visitors = \App\Models\Setting::get('site.visitor_count', 0);
  $lastUpdated = \App\Models\Setting::get('site.last_updated');
  $lastUpdatedFmt = $lastUpdated ? \Illuminate\Support\Carbon::parse($lastUpdated)->isoFormat('D MMM Y') : '';
@endphp
<footer class="bg-footer text-white pt-12 pb-6 no-print">
    <div class="container-page">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <img src="{{ asset('images/jata-negara.png') }}" alt="Jata Negara" class="h-10 w-auto object-contain flex-shrink-0">
                    <img src="{{ asset('images/logo-jkptg.png') }}" alt="JKPTG" class="h-10 w-auto object-contain flex-shrink-0 brightness-0 invert">
                </div>
                <address class="not-italic text-sm text-white/80 leading-relaxed">
                    {{ $address }}
                </address>
                <div class="mt-3 text-sm text-white/80 space-y-1">
                    <div class="flex items-center gap-2"><x-heroicon-o-phone class="w-4 h-4" /><span>{{ $phone }}</span></div>
                    <div class="flex items-center gap-2"><x-heroicon-o-envelope class="w-4 h-4" /><span>{{ $email }}</span></div>
                </div>
            </div>
            <div>
                <h3 class="font-semibold mb-3 text-jata-yellow">{{ __('messages.footer.pautan_pantas') }}</h3>
                <ul class="text-sm space-y-2 text-white/80">
                    <li><a href="{{ route('faq.index') }}" class="hover:text-white">{{ __('messages.utility.soalan_lazim') }}</a></li>
                    <li><a href="{{ route('panduan.index') }}" class="hover:text-white">{{ __('messages.nav.panduan') }}</a></li>
                    <li><a href="{{ route('borang.index') }}" class="hover:text-white">{{ __('messages.borang.title') }}</a></li>
                    <li><a href="{{ route('hubungi.aduan') }}" class="hover:text-white">{{ __('messages.utility.aduan') }}</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-3 text-jata-yellow">{{ __('messages.footer.korporat') }}</h3>
                <ul class="text-sm space-y-2 text-white/80">
                    <li><a href="{{ route('page.show', 'mengenai-jkptg') }}" class="hover:text-white">{{ __('messages.footer.mengenai') }}</a></li>
                    <li><a href="{{ route('page.show', 'piagam-pelanggan') }}" class="hover:text-white">{{ __('messages.footer.piagam') }}</a></li>
                    <li><a href="{{ route('page.show', 'kerjaya') }}" class="hover:text-white">{{ __('messages.footer.kerjaya') }}</a></li>
                    <li><a href="{{ route('hubungi.index') }}" class="hover:text-white">{{ __('messages.utility.hubungi') }}</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-3 text-jata-yellow">{{ __('messages.footer.sosial') }}</h3>
                <div class="flex gap-3 mb-4">
                    <a href="{{ \App\Models\Setting::get('social.facebook', '#') }}" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center" aria-label="Facebook">
                        <x-heroicon-o-globe-alt class="w-4 h-4" />
                    </a>
                    <a href="{{ \App\Models\Setting::get('social.twitter', '#') }}" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center" aria-label="X / Twitter">
                        <x-heroicon-o-megaphone class="w-4 h-4" />
                    </a>
                    <a href="{{ \App\Models\Setting::get('social.youtube', '#') }}" class="w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center" aria-label="YouTube">
                        <x-heroicon-o-play class="w-4 h-4" />
                    </a>
                </div>
                <h3 class="font-semibold mb-2 text-jata-yellow text-sm">{{ __('messages.footer.kata_kunci') }}</h3>
                <div class="flex flex-wrap gap-1.5 text-xs">
                    <a href="{{ route('search.index', ['q' => 'pengambilan tanah']) }}" class="bg-white/10 hover:bg-white/20 px-2 py-1 rounded">pengambilan tanah</a>
                    <a href="{{ route('search.index', ['q' => 'pusaka']) }}" class="bg-white/10 hover:bg-white/20 px-2 py-1 rounded">pusaka</a>
                    <a href="{{ route('search.index', ['q' => 'pajakan']) }}" class="bg-white/10 hover:bg-white/20 px-2 py-1 rounded">pajakan</a>
                    <a href="{{ route('borang.index') }}" class="bg-white/10 hover:bg-white/20 px-2 py-1 rounded">borang</a>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 pt-6 flex flex-wrap items-center justify-between gap-3 text-xs text-white/70">
            <div class="flex flex-wrap gap-x-4 gap-y-1">
                <a href="{{ route('page.show', 'disclaimer') }}" class="hover:text-white">{{ __('messages.footer.disclaimer') }}</a>
                <a href="{{ route('page.show', 'polisi-privasi') }}" class="hover:text-white">{{ __('messages.footer.polisi_privasi') }}</a>
                <a href="{{ route('page.show', 'polisi-keselamatan') }}" class="hover:text-white">{{ __('messages.footer.polisi_keselamatan') }}</a>
                <a href="{{ route('hak-cipta') }}" class="hover:text-white">{{ __('messages.footer.hak_cipta') }}</a>
                <a href="{{ route('dasar-web') }}" class="hover:text-white">{{ __('messages.peta_laman.dasar_web') }}</a>
                <a href="{{ route('peta-laman') }}" class="hover:text-white">{{ __('messages.footer.peta_laman') }}</a>
            </div>
            <div>
                {{ __('messages.footer.hak_cipta') }} &copy; {{ date('Y') }} JKPTG.
                {{ __('messages.footer.pelawat') }}: <strong>{{ number_format($visitors) }}</strong>.
                @if($lastUpdatedFmt) {{ __('messages.footer.kemaskini') }}: {{ $lastUpdatedFmt }}. @endif
            </div>
        </div>
    </div>
</footer>
