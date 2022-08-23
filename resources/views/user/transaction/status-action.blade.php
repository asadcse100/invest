@extends('user.layouts.master')

@section('title', __(':Type Funds', ['type' => ucfirst(data_get($transaction, 'type'))]))

@section('content')
    @include('user.transaction.'.$contentBlade, [
        "transaction" => $transaction,
        "status" => $status ?? null,
    ])
@endsection
