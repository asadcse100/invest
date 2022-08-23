@if(!empty(sys_settings('og_title')))    
	<meta property="og:title" content="{{ sys_settings('og_title') }} | {{ site_info('name') }}">
@else
	<meta property="og:title" content="@yield('title') | {{ site_info('name') }}">
@endif
@if(!empty(sys_settings('og_description')))
	<meta property="og:description" content="{{ sys_settings('og_description') }}">
@else
	<meta property="og:description" content="@yield('desc')">
@endif
@if(!empty(sys_settings('og_image')))    
	<meta property="og:image" content="{{ sys_settings('og_image') }}">
@endif
	<meta property="og:type" content="website">
	<meta property="og:url" content="{{ url()->current() }}"> 
	