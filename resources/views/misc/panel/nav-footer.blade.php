@php 

$nav_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';

$nav_title = (isset($attr['heading']) && $attr['heading']) ? true : false;
$nav_parent = (isset($attr['parent']) && $attr['parent']) ? true : false;
$nav_alone = (isset($attr['alone']) && $attr['alone']) ? true : false;

@endphp

@if(filled($items))
	@if($nav_parent)
	<div class="nk-footer-links">
	@endif
	
	@if($nav_alone)
	<ul class="nav nav-sm{{ $nav_class }}">
	@endif

		@foreach ($items as $menu)
		@if( (Auth::check() && $menu->access =='login') || ($menu->access =='public') )
		<li class="nav-item{{ (request()->url()==$menu->link) ? ' active' : '' }}">
			<a class="nav-link" href="{{ $menu->link }}"{!! ($menu->menu_link) ? ' target="_blank"' : '' !!}>{{ __($menu->text) }}</a>
		</li>
		@endif
		@endforeach

		{!! Panel::lang_switcher() !!}

	@if($nav_alone)
	</ul>
	@endif
	
	@if($nav_parent)
	</div>
	@endif

@endif