@extends('layouts.public')

@section('title', __('messages.sumber.data_terbuka') . ' | ' . __('messages.site_name'))

@section('content')
<x-breadcrumb :items="[
    ['label' => __('messages.nav.sumber'), 'href' => route('sumber.index')],
    ['label' => __('messages.sumber.data_terbuka')],
]" />

<section class="bg-gradient-to-br from-primary to-primary-mute text-white py-12">
    <div class="container-page">
        <div class="flex items-center gap-2 text-jata-yellow text-sm uppercase tracking-wider mb-2">
            <x-heroicon-o-chart-bar class="w-4 h-4" />
            <span>{{ __('messages.sumber.data_terbuka') }}</span>
        </div>
        <h1 class="font-display text-3xl md:text-5xl font-bold mb-3">{{ __('messages.sumber.data_terbuka') }}</h1>
        <p class="text-white/85 max-w-2xl">{{ __('messages.sumber.data_desc') }}</p>
    </div>
</section>

<section class="py-12">
    <div class="container-page">
        <div class="bg-white border rounded-lg overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-left text-xs uppercase tracking-wider text-gray-600">
                    <tr>
                        <th class="px-4 py-3">{{ __('messages.sumber.data_col_name') }}</th>
                        <th class="px-4 py-3 hidden md:table-cell">{{ __('messages.sumber.data_col_desc') }}</th>
                        <th class="px-4 py-3">{{ __('messages.sumber.data_col_format') }}</th>
                        <th class="px-4 py-3">{{ __('messages.sumber.data_col_size') }}</th>
                        <th class="px-4 py-3 text-right">{{ __('messages.sumber.data_col_action') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($datasets as [$name, $desc, $format, $size])
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-semibold text-primary">{{ $name }}</td>
                            <td class="px-4 py-3 text-gray-600 hidden md:table-cell">{{ $desc }}</td>
                            <td class="px-4 py-3"><span class="inline-block px-2 py-0.5 rounded bg-primary-pale text-primary text-xs font-bold">{{ $format }}</span></td>
                            <td class="px-4 py-3 text-gray-600">{{ $size }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-xs text-gray-400 italic">{{ __('messages.sumber.data_coming_soon') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="mt-4 text-xs text-gray-500">{{ __('messages.sumber.data_license') }}</p>
    </div>
</section>
@endsection
