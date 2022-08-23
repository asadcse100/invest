@extends('errors::custom')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('heading', __("Unauthorized!"))
@section('message', __("Sorry, you do not have permission to access this resource."))