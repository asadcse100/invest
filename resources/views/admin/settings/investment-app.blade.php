@extends('admin.layouts.master')
@section('title', __('Investment App Setting'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
{{--    Investment app configure setting markup goes here--}}
@endsection
