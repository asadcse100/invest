@php 

$menu_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';

$menu_title = (isset($attr['heading']) && $attr['heading']) ? true : false;
$menu_parent = (isset($attr['parent']) && $attr['parent']) ? true : false;
$menu_alone = (isset($attr['alone']) && $attr['alone']) ? true : false;

@endphp

@if(filled($items))

	@if($menu_parent)
	<div class="mainmenu-nav{{ $menu_class }}">
	@endif

	@if($menu_alone)
	<ul class="menu-list{{ $menu_class }}">
	@endif

		@foreach ($items as $menu)
		@if( (Auth::check() && $menu->access =='login') || ($menu->access =='public') )
		<li class="menu-item{{ (request()->url()==$menu->link) ? ' active' : '' }}">
		    <a href="{{ $menu->link }}" class="menu-link nav-link"{!! ($menu->menu_link) ? ' target="_blank"' : '' !!}>
		        <span class="menu-text">{{ __($menu->text) }}</span>
		    </a>
		</li>
		@endif
		@endforeach
	
	@if($menu_alone)
	</ul>
	@endif
	
	@if($menu_parent)
	</div>
	@endif

@endif