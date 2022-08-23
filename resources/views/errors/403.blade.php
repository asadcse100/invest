@extends('errors::custom')

@section('title', __('Forbidden'))
@section('code', '403')
@section('heading', __("Access is denied!"))
@section('message', __("Sorry but you don't have permission to access the page."))
