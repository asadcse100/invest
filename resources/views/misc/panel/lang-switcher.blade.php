@php

$witcher_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';

$switcher = sys_settings('language_switcher');
$showAs = sys_settings('language_show_as', 'default');
$ddSize = ($showAs == 'short') ? 'xs' : 'sm';
$trigger = ($showAs == 'code') ? strtoupper($selected['code']) : ucfirst($selected['label']);

@endphp

@if($switcher=='on' && !empty($langs))

@if ($type == 'auth')
<div class="lang-switcher {{ $witcher_class }}">
	<ul class="nav nav-sm">
@endif

	<li class="{{ ($type == 'sidebar') ? 'nk-menu' : 'nav' }}-item{{ ($type != 'auth') ? $witcher_class : '' }}">
		<div class="dropup">
			<a href="javascript:void(0)" class="dropdown-toggle dropdown-indicator has-indicator{{ ($type == 'sidebar') ? ' nk-menu-link' : ' nav-link' }} lang-switch-btn toggle-tigger" data-toggle="dropdown">
				@if ($type == 'sidebar')
				<span class="nk-menu-icon"><em class="icon ni ni-globe"></em></span>
				<span class="nk-menu-text">{{ $trigger }}</span>
				@else 
				{{ $trigger }}
				@endif
			</a>
			<div class="dropdown-menu dropdown-menu-{{ ($showAs == 'code') ? 'xxs' : $ddSize }} dropdown-menu-right">
				<ul class="language-list">
					@foreach ($langs as $code => $name)
						<li>
							<a href="{{ route('language', ['lang' => $code]) }}" class="language-item justify-center">
								<span class="language-name{{ ($code == $selected['code']) ? ' fw-medium' : ''}}">{{ ($showAs == 'code') ? strtoupper($code) : ucfirst($name) }}</span>
							</a>
						</li>
					@endforeach
				</ul>
			</div>
		</div>
	</li>

@if ($type == 'auth')
	</ul>
</div>
@endif

@endif
