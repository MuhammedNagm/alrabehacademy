@php
$media = \LMS::getEmbededLink($embeded);
$height = $height??'450px';
@endphp
@if (strpos($media['link'], 'youtube') !== false)
	<div class="plyr__video-embed js-player" style="height: {{$height}}">
		<iframe
				src="{{$media['link']}}?origin=https://alrabehacademy.com/&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=0&amp;vq=hd720"
				allowfullscreen
				allowtransparency
				allow="autoplay"
		></iframe>
	</div>
@else
	<div >
		<style>.embed-container { position: relative; padding-bottom: 56.25%;  overflow: hidden; max-width: 100%; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class='embed-container' style="height: {{$height}} !important;"><iframe src='{{$media['link']}}' frameborder='0' allowfullscreen sandbox="allow-forms allow-scripts allow-pointer-lock allow-same-origin allow-top-navigation"></iframe></div>
	</div>
@endif

