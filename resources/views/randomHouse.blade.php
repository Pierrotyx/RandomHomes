<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<?php
		if( env('APP_ENV') == 'production' )
		{
			?>
			<!-- Google tag (gtag.js) -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=G-36S4FCKE0S"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments);}
			  gtag('js', new Date());

			  gtag('config', 'G-36S4FCKE0S');
			</script>
			<?php
		}
		?>
		<meta charset="UTF-8">
		<link rel="icon" type="image/x-icon" href="{{ asset( '/favicon.ico' ) }}" sizes="16x16 32x32">
		<link rel="icon" type="image/png" href="{{ asset( '/favicon.ico' ) }}" sizes="16x16 32x32">
		<link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset( '/favicon.ico' ) }}">
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
		<meta name="csrf-token" content="{{ csrf_token() }}" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		
		<link href="{{ asset( 'assets/css/style.css' ) }}?time=6" rel="stylesheet" />
		<link rel="canonical" href="{{ url()->current() }}">
		@include( $head )
	</head>
	<body>
		<div id="overlay">
			<div id="loader" class="loader"></div>
		</div>
		<?php
		if( !empty( $header ) )
		{
			?>
			<div class="header">	
				@include( $header )
			</div>
			<?php
		}
		?>
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
		<script type="text/javascript" src="{{asset('assets/js/main.js')}}?time=6"></script>
		<script>
			var interval = '1 day';
			var leaderboardType = 'place5';
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
				if( '{{request()->type ?? ''}}' == 'ForRent' )
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
