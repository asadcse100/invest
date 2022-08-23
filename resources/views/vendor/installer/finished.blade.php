@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.final.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.final.title') }}
@endsection

@section('container')

	@if(session('message')['dbOutputLog'])
		<p><strong><small>{{ trans('installer_messages.final.migration') }}</small></strong></p>
		<pre style="max-height: 300px; padding: 5px 10px;"><code>{{ session('message')['dbOutputLog'] }}</code></pre>
	@endif

	@php 
		$cacheClear = Artisan::call('cache:clear');
        $viewClear = Artisan::call('view:clear');
        $prntLogs = 'Application cache cleared and optimized!';
	@endphp

	<p><strong><small>{{ trans('installer_messages.final.console') }}</small></strong></p>
	<pre style="padding: 5px 10px;"><code>{{ $finalMessages . "\r\n" . $prntLogs }}</code></pre>

	<p><strong><small>{{ trans('installer_messages.final.log') }}</small></strong></p>
	<pre style="padding: 5px 10px;"><code>{{ $finalStatusMessage }}</code></pre>

	{{-- <p><strong><small>{{ trans('installer_messages.final.env') }}</small></strong></p> 
	<pre><code>{{ $finalEnvFile }}</code></pre> --}}

    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>

@endsection
