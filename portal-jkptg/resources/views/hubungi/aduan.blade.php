@extends('layouts.public')

@section('title', __('messages.utility.aduan') . ' | ' . __('messages.site_name'))

@section('content')
<x-statement-band :label="__('messages.utility.aduan')" :title="__('messages.aduan.heading')" :subtitle="__('messages.aduan.help')" />

<x-breadcrumb :items="[
    ['label' => __('messages.utility.hubungi'), 'href' => route('hubungi.index')],
    ['label' => __('messages.utility.aduan')],
]" />

<section class="py-12">
    <div class="container-page max-w-3xl">
        @if(session('aduan_reference'))
            <div role="status" class="mb-6 rounded-lg border border-green-300 bg-green-50 p-5">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-700 flex-shrink-0" />
                    <div>
                        <h2 class="font-bold text-green-900 mb-1">{{ __('messages.aduan.success_title') }}</h2>
                        <p class="text-sm text-green-800">{{ __('messages.aduan.success_body') }}</p>
                        <p class="mt-2 text-sm">
                            <span class="font-semibold">{{ __('messages.aduan.reference_label') }}:</span>
                            <code class="bg-white px-2 py-0.5 rounded border border-green-300 font-mono text-green-900">{{ session('aduan_reference') }}</code>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div role="alert" class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4">
                <div class="flex items-start gap-3">
                    <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-red-700 flex-shrink-0" />
                    <div>
                        <h2 class="font-bold text-red-900 mb-1">{{ __('messages.aduan.error_title') }}</h2>
                        <ul class="list-disc list-inside text-sm text-red-800 space-y-0.5">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('hubungi.aduan.store') }}" class="bg-white border rounded-lg p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <label class="block">
                    <span class="text-sm font-semibold text-gray-700">{{ __('messages.aduan.name') }} <span class="text-red-600">*</span></span>
                    <input type="text" name="name" required maxlength="120" value="{{ old('name') }}"
                           class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-gray-700">{{ __('messages.aduan.email') }} <span class="text-red-600">*</span></span>
                    <input type="email" name="email" required maxlength="160" value="{{ old('email') }}"
                           class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-gray-700">{{ __('messages.aduan.phone') }}</span>
                    <input type="tel" name="phone" maxlength="32" value="{{ old('phone') }}"
                           class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm" />
                </label>
                <label class="block">
                    <span class="text-sm font-semibold text-gray-700">{{ __('messages.aduan.category') }} <span class="text-red-600">*</span></span>
                    <select name="category" required
                            class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm">
                        <option value="">{{ __('messages.aduan.select_category') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" @selected(old('category') === $cat)>{{ __('messages.aduan.cat.' . str_replace('-', '_', $cat)) }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <label class="block">
                <span class="text-sm font-semibold text-gray-700">{{ __('messages.aduan.subject') }} <span class="text-red-600">*</span></span>
                <input type="text" name="subject" required maxlength="200" value="{{ old('subject') }}"
                       class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm" />
            </label>

            <label class="block">
                <span class="text-sm font-semibold text-gray-700">{{ __('messages.aduan.message') }} <span class="text-red-600">*</span></span>
                <textarea name="message" required rows="6" maxlength="4000"
                          class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm">{{ old('message') }}</textarea>
            </label>

            <p class="text-xs text-gray-500 leading-relaxed">{{ __('messages.aduan.privacy') }}</p>

            <div class="flex items-center gap-3 justify-end">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-semibold px-6 py-2 rounded transition focus:outline-none focus:ring-2 focus:ring-jata-yellow focus:ring-offset-2">
                    {{ __('messages.aduan.submit') }}
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
