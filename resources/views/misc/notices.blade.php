@if (!empty($errors) && !is_array($errors) && $errors->any())
<div class="alert-notices mb-4">
    <ul>
        @foreach ($errors->toArray() as $type => $error)
        <li class="alert alert-{{ (in_array($type, ['warning', 'info', 'success', 'light'])) ? $type : 'danger' }} alert-icon alert-dismissible">
            <em class="icon ni ni-alert-fill"></em> {!! $error[0] ?? '' !!} <button class="close" data-dismiss="alert"></button>
        </li>
        @endforeach
    </ul>
</div>
@endif

@if (session('notice'))
<div class="alert alert-dismissible alert-icon alert-info">
	<em class="icon ni ni-info-fill"></em> {{ session('notice') }}
    <button class="close" data-dismiss="alert"></button>
</div>
@endif

@if (session('warning'))
<div class="alert alert-dismissible alert-icon alert-warning">
	<em class="icon ni ni-alert-fill"></em> {{ session('warning') }}
    <button class="close" data-dismiss="alert"></button>
</div>
@endif

@if (session('success'))
<div class="alert alert-dismissible alert-icon alert-success">
	<em class="icon ni ni-check-circle-fill"></em> {{ session('success') }}
    <button class="close" data-dismiss="alert"></button>
</div>
@endif