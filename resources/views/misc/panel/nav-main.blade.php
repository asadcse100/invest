@php 

$menu_class = (isset($attr['class']) && !empty($attr['class'])) ? ' '.$attr['class'] : '';

$menu_title = (isset($attr['heading']) && $attr['heading']) ? true : false;
$menu_parent = (isset($attr['parent']) && $attr['parent']) ? true : false;
$menu_alone = (isset($attr['alone']) && $attr['alone']) ? true : false;

@endphp

@if(filled($items) || (gss('front_page_enable', 'yes')=='yes' || !empty(gss('main_website'))))

	@if($menu_parent)
	<div class="nk-main-menu{{ $menu_class }}">
	@endif

	@if($menu_alone)
	<ul class="nk-menu{{ $menu_class }}">
	@endif

	@if(sys_settings('main_menu_heading', __("Additional")) && $menu_title)
	<li class="nk-menu-heading">
	    <h6 class="overline-title">{{ __(sys_settings('main_menu_heading', __("Additional"))) }}</h6>
	</li>
	@endif

		@if(gss('front_page_enable', 'yes')=='yes')
		<li class="nk-menu-item">
		    <a href="{{ url('/') }}" class="nk-menu-link">
		        <span class="nk-menu-text">{{ __("Go to Home") }}</span>
		    </a>
		</li>
		@elseif (!empty(gss('main_website')))
		<li class="nk-menu-item">
		    <a href="{{ gss('main_website') }}" target="_blank" class="nk-menu-link">
		        <span class="nk-menu-text">{{ __("Go to Main Website") }}</span>
		    </a>
		</li>
		@endif

		@foreach ($items as $menu)
		@if( (Auth::check() && $menu->access =='login') || ($menu->access =='public') )
		<li class="nk-menu-item{{ (request()->url()==$menu->link) ? ' active' : '' }}">
		    <a href="{{ $menu->link }}" class="nk-menu-link"{!! ($menu->menu_link) ? ' target="_blank"' : '' !!}>
		        <span class="nk-menu-text">{{ __($menu->text) }}</span>
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
