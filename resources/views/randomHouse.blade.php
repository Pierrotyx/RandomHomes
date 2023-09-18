<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		@include( $head )
	</head>
	<body>
		<div id="overlay">
			<div id="loader" class="loader"></div>
		</div>
		<div class="container">
			@include( 'templates.sidebar' )
			<div class="screen-wrap">
				@include( $body, $parameters ?? [] )
			</div>
		</div>
		<br>
		<?php
		if( !empty( $description ) )
		{
			?>
			<div class="description">	
				@include( $description )
			</div>
			<?php
		}
		?>
		@include( 'templates.footer' )
		<script type="text/javascript" src="{{asset('assets/js/main.js')}}"></script>
		<script>
			window.onload = function() {
				// Check if some condition is met (e.g., a failed request)
				var conditionFailed = true; // Replace this with your actual condition

				if( {{ !empty( request('fail') ) ? 1 : 0 }} )
				{
					alert('Unfortunately, no properties matching your search criteria were found. Please broaden your filters for better results.');
				}
				
				if( $(window).width() > 750 )
				{
					$('#{{$startIcon}}-icon').addClass( 'pressed');
					$('#{{$startIcon}}-links').show();
				}
			};

			$(document).ready(function() {
				if( '{{Session::get('type') ?? ''}}' == 'ForRent' || '{{request()->type ?? ''}}' == 'ForRent' )
				{
					$('#sale-filters, #rent-filters, #submit-rent, #submit-sale').toggle();
				}
			 });
		</script>
		<?php
		if( env('APP_ENV') == 'production' )
		{
			?>
			<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5564950322081391" crossorigin="anonymous"></script>
			<?php
		}
		?>
	</body>
</html>
