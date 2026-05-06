@extends('pdf.layout')
@section('title', 'SOP - ' . $service->name)
@section('heading', $service->name)
@section('meta')
{{ __('messages.service.sop_print_title') }} · {{ __('messages.service.processing_time') }}: {{ $service->processing_days }} {{ __('messages.service.days') }}
@endsection
@section('content')
    <p>{{ $service->summary }}</p>
    <h2>{{ __('messages.service.proses') }}</h2>
    @foreach(($service->process_steps ?? []) as $i => $step)
        <div class="step"><span class="step-num">{{ $i + 1 }}</span><strong>{{ $step }}</strong></div>
    @endforeach
    @if($service->getTranslations('eligibility'))
        <h2>{{ __('messages.service.kelayakan') }}</h2>
        <p>{{ $service->eligibility }}</p>
    @endif
    @php $docs = $service->required_documents ?? []; @endphp
    @if(!empty($docs))
        <h2>{{ __('messages.service.dokumen') }}</h2>
        <ul>
            @foreach($docs as $d)<li>{{ $d }}</li>@endforeach
        </ul>
    @endif
    <div class="disclaimer">
        <strong>{{ __('messages.service.sop_disclaimer_title') }}.</strong>
        {{ __('messages.service.sop_disclaimer_body') }}
    </div>
@endsection
