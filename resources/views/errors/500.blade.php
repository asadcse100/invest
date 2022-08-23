@extends('errors::custom')

@section('title', __('Internal Server Error'))
@section('code', '500')
@section('heading', __('Sorry, something went wrong!'))
@section('message', __("We're sorry for inconvenience. Please try again later or feel free to contact us if the problem persists."))
