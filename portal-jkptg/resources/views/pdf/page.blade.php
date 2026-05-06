@extends('pdf.layout')
@section('title', $page->title)
@section('heading', $page->title)
@section('meta'){{ $page->meta_description ?? '' }}@endsection
@section('content')
    {!! $page->body !!}
@endsection
