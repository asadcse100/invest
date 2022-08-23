@extends('errors::custom')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('heading', __('Service unavailable!'))
@section('message', __('The server is temporarily unable to service your request due to maintaince or downtime or capacity problems. Please try again later or feel free to contact us if the problem persists.'))
