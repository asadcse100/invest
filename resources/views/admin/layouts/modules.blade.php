@extends('admin.layouts.master')
@section('title', __('Module Setting'))

@section('has-content-sidebar', 'has-content-sidebar')

@section('content-sidebar')
@include('admin.settings.content-sidebar')
@endsection
