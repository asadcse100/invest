@if ($errors->any())
<div class="alert-notices mb-4">
    <ul>
        @foreach ($errors->toArray() as $type => $error)
        <li class="alert alert-{{ (in_array($type, ['warning', 'info', 'success', 'light'])) ? $type : 'danger' }} alert-icon">
            <em class="icon ni ni-alert-fill"></em> {!! $error[0] ?? '' !!}
        </li>
        @endforeach
    </ul>
</div>
@endif

@if(session()->has('unknown_error'))
<div class="alert alert-warning">
    <ul>
        <li class="alert-icon centered"><em class="icon ni ni-alert-fill"></em>{{ session()->pull('unknown_error') }}</li>
    </ul>
</div>

@endif
