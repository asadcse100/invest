@php 

$footer 	= isset($footer) ? $footer : false;
$ft_wide	= (isset($footer['wide'])) ? $footer['wide'] : '';
$ft_style	= (isset($footer['style'])) ? $footer['style'] : 'regular';
$ft_class 	= (isset($footer['class'])) ? ' '.$footer['class'] : '';

@endphp

<div class="nk-footer{{ $ft_class }}{{ (gui('user', 'sidebar')=='lighter') ? ' bg-lighter' : '' }}">
	<div class="{{ (($ft_wide) ? 'container wide-'.$ft_wide : 'container-fluid') }}">
		<div class="nk-footer-wrap">
		    <div class="nk-footer-copyright">{!! __(site_info('copyright')) !!}</div>
		    {!! Panel::navigation('footer', ['parent' => true]) !!}
		</div>
	</div>
</div>