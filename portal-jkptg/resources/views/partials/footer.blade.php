@php
  $address = \App\Models\Setting::get('site.address');
  $address = is_array($address) ? ($address[app()->getLocale()] ?? '') : $address;
  $phone = \App\Models\Setting::get('site.phone', '03-8000 8000');
  $email = \App\Models\Setting::get('site.email', 'webmaster@jkptg.gov.my');
  $visitors = \App\Models\Setting::get('site.visitor_count', 0);
  $lastUpdated = \App\Models\Setting::get('site.last_updated');
  $lastUpdatedFmt = $lastUpdated ? \Illuminate\Support\Carbon::parse($lastUpdated)->format('Y-m-d') : '';
@endphp
<footer class="bg-footer text-white pt-16 pb-8 no-print">
    <div class="container-page">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 mb-12">
            {{-- Brand + address --}}
            <div class="md:col-span-5">
                <div class="flex items-center gap-3 mb-5">
                    <img src="{{ asset('images/jata-negara.png') }}" alt="Jata Negara" class="h-11 w-auto object-contain flex-shrink-0">
                    <img src="{{ asset('images/logo-jkptg.png') }}" alt="JKPTG" class="h-9 w-auto object-contain flex-shrink-0 brightness-0 invert">
                </div>
                <div class="text-[11px] uppercase tracking-[0.16em] font-semibold text-bronze-light mb-2">{{ __('messages.utility.official_short') }}</div>
                <div class="font-bold text-[15px] text-white leading-snug max-w-md">
                    {{ __('messages.site_description') }}
                </div>
                <div class="text-[13px] text-white/60 mb-5 leading-snug max-w-md">
                    {{ __('messages.ministry_name') }}
                </div>
                <address class="not-italic text-[13px] text-white/70 leading-relaxed max-w-md mb-4">
                    {{ $address }}
                </address>
                <dl class="text-[13px] text-white/80 space-y-1.5">
                    <div class="flex gap-3">
                        <dt class="text-[11px] uppercase tracking-[0.14em] font-semibold text-white/45 w-14 shrink-0 pt-0.5">{{ app()->getLocale() === 'ms' ? 'Tel' : 'Tel' }}</dt>
                        <dd class="font-medium">{{ $phone }}</dd>
                    </div>
                    <div class="flex gap-3">
                        <dt class="text-[11px] uppercase tracking-[0.14em] font-semibold text-white/45 w-14 shrink-0 pt-0.5">{{ app()->getLocale() === 'ms' ? 'Emel' : 'Email' }}</dt>
                        <dd><a href="mailto:{{ $email }}" class="font-medium hover:text-white">{{ $email }}</a></dd>
                    </div>
                </dl>
            </div>

            {{-- Pautan Pantas --}}
            <div class="md:col-span-3">
                <h3 class="text-[12px] uppercase tracking-[0.16em] font-semibold text-bronze-light mb-4">{{ __('messages.footer.pautan_pantas') }}</h3>
                <ul class="text-[14px] space-y-2.5 text-white/85">
                    <li><a href="{{ route('faq.index') }}" class="hover:text-white">{{ __('messages.utility.soalan_lazim') }}</a></li>
                    <li><a href="{{ route('panduan.index') }}" class="hover:text-white">{{ __('messages.nav.panduan') }}</a></li>
                    <li><a href="{{ route('borang.index') }}" class="hover:text-white">{{ __('messages.borang.title') }}</a></li>
                    <li><a href="{{ route('hubungi.aduan') }}" class="hover:text-white">{{ __('messages.utility.aduan') }}</a></li>
                    <li><a href="{{ route('search.index') }}" class="hover:text-white">{{ __('messages.search.input_label') }}</a></li>
                </ul>
            </div>

            {{-- Korporat --}}
            <div class="md:col-span-2">
                <h3 class="text-[12px] uppercase tracking-[0.16em] font-semibold text-bronze-light mb-4">{{ __('messages.footer.korporat') }}</h3>
                <ul class="text-[14px] space-y-2.5 text-white/85">
                    <li><a href="{{ route('page.show', 'mengenai-jkptg') }}" class="hover:text-white">{{ __('messages.footer.mengenai') }}</a></li>
                    <li><a href="{{ route('page.show', 'piagam-pelanggan') }}" class="hover:text-white">{{ __('messages.footer.piagam') }}</a></li>
                    <li><a href="{{ route('page.show', 'kerjaya') }}" class="hover:text-white">{{ __('messages.footer.kerjaya') }}</a></li>
                    <li><a href="{{ route('hubungi.index') }}" class="hover:text-white">{{ __('messages.utility.hubungi') }}</a></li>
                </ul>
            </div>

            {{-- Sumber --}}
            <div class="md:col-span-2">
                <h3 class="text-[12px] uppercase tracking-[0.16em] font-semibold text-bronze-light mb-4">{{ __('messages.nav.sumber') }}</h3>
                <ul class="text-[14px] space-y-2.5 text-white/85">
                    <li><a href="{{ route('sumber.index') }}" class="hover:text-white">{{ __('messages.nav.sumber') }}</a></li>
                    <li><a href="{{ route('panduan.index') }}" class="hover:text-white">{{ __('messages.nav.panduan') }}</a></li>
                    <li><a href="{{ route('peta-laman') }}" class="hover:text-white">{{ __('messages.footer.peta_laman') }}</a></li>
                    <li><a href="{{ route('dasar-web') }}" class="hover:text-white">{{ __('messages.peta_laman.dasar_web') }}</a></li>
                </ul>
            </div>
        </div>

        {{-- Legal strip --}}
        <div class="border-t border-white/10 pt-6 flex flex-wrap items-start justify-between gap-x-6 gap-y-3 text-[12px] text-white/60">
            <nav aria-label="Legal" class="flex flex-wrap gap-x-5 gap-y-1.5">
                <a href="{{ route('page.show', 'disclaimer') }}" class="hover:text-white">{{ __('messages.footer.disclaimer') }}</a>
                <a href="{{ route('page.show', 'polisi-privasi') }}" class="hover:text-white">{{ __('messages.footer.polisi_privasi') }}</a>
                <a href="{{ route('page.show', 'polisi-keselamatan') }}" class="hover:text-white">{{ __('messages.footer.polisi_keselamatan') }}</a>
                <a href="{{ route('hak-cipta') }}" class="hover:text-white">{{ __('messages.footer.hak_cipta') }}</a>
                <a href="{{ route('dasar-web') }}" class="hover:text-white">{{ __('messages.peta_laman.dasar_web') }}</a>
                <a href="{{ route('peta-laman') }}" class="hover:text-white">{{ __('messages.footer.peta_laman') }}</a>
            </nav>
            <dl class="flex flex-wrap items-center gap-x-6 gap-y-1.5 text-[12px]">
                <div class="flex gap-2">
                    <dt class="text-white/45">{{ __('messages.footer.pelawat') }}:</dt>
                    <dd class="text-white/85 font-semibold">{{ number_format($visitors) }}</dd>
                </div>
                @if($lastUpdatedFmt)
                <div class="flex gap-2">
                    <dt class="text-white/45">{{ __('messages.footer.kemaskini') }}:</dt>
                    <dd class="text-white/85 font-semibold">{{ \Illuminate\Support\Carbon::parse($lastUpdated)->isoFormat('D MMM Y') }}</dd>
                </div>
                @endif
                <div class="flex gap-2">
                    <dt class="text-white/45">© {{ date('Y') }}</dt>
                    <dd class="text-white/85 font-semibold">JKPTG</dd>
                </div>
            </dl>
        </div>
    </div>
</footer>
