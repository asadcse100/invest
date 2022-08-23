@php 

$color = (isset($attr['color']) && $attr['color']) ? $attr['color'] : false;
$social_class = (isset($attr['class']) && $attr['class']) ? ' '.$attr['class'] : '';
$social_parent = (isset($attr['parent']) && $attr['parent']) ? true : false;

$size = (isset($attr['size']) && $attr['size']) ? ' '.$attr['size'] : '';
$gaps = (isset($attr['gaps']) && $attr['gaps']) ? ' '.$attr['gaps'] : '';

$color_class = ($color=='auto') ? ' text-soft' : ((!empty($color)) ? ' text-'.$color : '');

@endphp

@if(!empty($socials) && count($socials) > 0)
@if($social_parent)
<ul class="nk-socials{{ $social_class.$gaps }}">
@endif

	@foreach ($socials as $item)
	<li class="social-item">
		<a class="social-link{{ $color_class.$size }}" href="{{ $item['link'] }}" title="{{ $item['title'] }}" target="_blank"><em class="icon ni ni-{{ $item['icon'] }}"></em></a>
	</li>
	@endforeach

@if($social_parent)
</ul>
@endif
@endif