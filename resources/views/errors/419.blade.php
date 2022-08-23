@extends('errors::custom')

@section('title', __('Page Expired'))
@section('code', '419')
@section('heading', __('Session has expired!'))
@section('message', __("Sorry but your session has expired. Please refresh and try again."))
