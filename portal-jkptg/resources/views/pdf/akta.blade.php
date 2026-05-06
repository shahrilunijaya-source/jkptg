@extends('pdf.layout')
@section('title', $act['title'])
@section('heading', $act['title'])
@section('meta')
{{ __('messages.panduan.akta_year') }}: {{ $act['year'] }} · {{ __('messages.panduan.akta_topic') }}: {{ ucfirst($act['topic']) }}
@endsection
@section('content')
    <h2>{{ __('messages.panduan.akta_h_overview') }}</h2>
    <p>{{ __('messages.panduan.akta_p_overview', ['title' => $act['title']]) }}</p>
    <h2>{{ __('messages.panduan.akta_h_scope') }}</h2>
    <p>{{ __('messages.panduan.akta_p_scope') }}</p>
    <h2>{{ __('messages.panduan.akta_h_admin') }}</h2>
    <p>{{ __('messages.panduan.akta_p_admin') }}</p>
    <h2>{{ __('messages.panduan.akta_h_full') }}</h2>
    <p>Sila lawati portal Pesuruhjaya Penyemak Undang-Undang (AGC) di www.agc.gov.my untuk teks akta penuh.</p>
    <div class="disclaimer">
        <strong>{{ __('messages.static.legal') }}.</strong>
        {{ __('messages.panduan.akta_disclaimer') }}
    </div>
@endsection
