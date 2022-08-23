@extends('errors::custom')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('heading', __('Sorry, too many requests!'))
@section('message', __("We're sorry, but you have sent too many requests to us recently. Please try after sometimes."))
