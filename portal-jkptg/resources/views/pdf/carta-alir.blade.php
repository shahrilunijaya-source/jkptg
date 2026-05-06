@extends('pdf.layout')
@section('title', 'Carta Alir - ' . $service->name)
@section('heading', $service->name)
@section('meta')
{{ __('messages.service.carta_alir_breadcrumb') }} · {{ __('messages.service.processing_time') }}: {{ $service->processing_days }} {{ __('messages.service.days') }}
@endsection
@section('content')
    <p>{{ __('messages.service.carta_alir_intro') }}</p>
    <div class="badge">{{ __('messages.service.carta_alir_start') }}</div>
    @foreach(($service->process_steps ?? []) as $i => $step)
        <div class="arrow">↓</div>
        <div class="step"><span class="step-num">{{ $i + 1 }}</span><strong>{{ $step }}</strong></div>
    @endforeach
    <div class="arrow">↓</div>
    <div class="badge badge-end">{{ __('messages.service.carta_alir_end') }}</div>
@endsection
