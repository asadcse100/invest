@extends('admin.layouts.master')
@section('title', __('Investment Account Setting'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
    @include('admin.settings.content-sidebar')
@endsection

@section('content')
{{--    Investment App account settings markup goes here--}}
@endsection
