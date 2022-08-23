@extends('errors::custom')

@section('title', __('Server Error'))
@section('code', '504')
@section('heading', __('Sorry, something went wrong!'))
@section('message', __('We are very sorry for inconvenience. It looks like some how our server did not receive a timely response.'))
