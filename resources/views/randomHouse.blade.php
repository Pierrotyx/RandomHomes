<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<!-- Google tag (gtag.js) -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-36S4FCKE0S"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-36S4FCKE0S');
		</script>
        <title>Random Homes | Discover Unique Homes and Properties</title>
		<link rel="icon" type="image/x-icon" href="https://www.randomhome.net/favicon.ico" sizes="16x16 32x32">
		<link rel="icon" type="image/png" href="https://www.randomhome.net/favicon.ico" sizes="16x16 32x32">
		<link rel="apple-touch-icon" type="image/png" sizes="180x180" href="https://www.randomhome.net/favicon.ico">
		
        <meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
		 <!-- Meta tags for SEO -->
		<meta name="description" content="Discover your dream home with RandomHome.net. Explore a diverse range of unique properties. Start your journey today!">
		<meta name="keywords" content="random homes, searching for properties, houses for houses, us houses">
		<meta name="author" content="Jarrod Amyx">

		<?php
		if( !empty( $results->imgSrc ) )
		{
			?>
			<!-- Open Graph meta tags -->
			<meta property="og:title" content="{{$results->address ?? 'Random Home'}}">
			<meta
				property="og:description"
				content="Price: {{$results->price ?? '--'}}, Beds: {{$results->bedrooms ?? '--'}}, Baths: {{$results->bathrooms ?? '--'}}, Living Size: {{$results->livingSize ?? '--'}}, Lot Size: {{$results->lotAreaSize ?? '--'}}"
			>
			<meta property="og:type" content="website">
			<meta property="og:url" content="https://{{ $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] }}">
			<meta property="og:image" content="{{$results->imgSrc ?? 'https://www.randomhome.net/DiceHouse.jpg'}}">
			<meta property="og:image:alt" content="Random Home Preview">

			<!-- Twitter Card meta tags -->
			<meta name="twitter:card" content="summary_large_image">
			<meta name="twitter:title" content="{{$results->address ?? 'Random Home'}}">
				<meta
					name="twitter:description"
					content="Price: {{$results->price ?? '--'}}, Beds: {{$results->bedrooms ?? '--'}}, Baths: {{$results->bathrooms ?? '--'}}, Living Size: {{$results->livingSize ?? '--'}}"
				>
			<meta name="twitter:image" content="{{$results->imgSrc}}">
			<meta name="twitter:image:alt" content="Random Home Preview">
			<?php
		}
		else
		{
			?>
						<!-- Open Graph meta tags -->
			<meta property="og:title" content="Random Home">
			<meta
				property="og:description"
				content="Discover your dream home with RandomHome.net. Explore a diverse range of unique properties. Start your journey today!"
			>
			<meta property="og:type" content="website">
			<meta property="og:url" content="https://{{ $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] }}">
			<meta property="og:image" content="https://www.randomhome.net/website.PNG">
			<meta property="og:image:alt" content="Random Home Preview">

			<!-- Twitter Card meta tags -->
			<meta name="twitter:card" content="summary_large_image">
			<meta name="twitter:title" content="{{$results->address ?? 'Random Home'}}">
				<meta
					name="twitter:description"
					content="Discover your dream home with RandomHome.net. Explore a diverse range of unique properties. Start your journey today!"
				>
			<meta name="twitter:image" content="https://www.randomhome.net/website.PNG">
			<meta name="twitter:image:alt" content="Random Home Preview">
			<?php
		}
		?>
		<meta charset="UTF-8">
	    <link rel="canonical" href="https://www.randomhome.net">

		<script type="application/ld+json">
		{
		  "@context": "http://schema.org",
		  "@type": "WebSite",
		  "name": "Random Home Finder",
		  "description": "Discover your dream home with RandomHome.net. Explore a diverse range of unique properties. Start your journey today!",
		  "url": "https://www.randomhome.net/",
		  "potentialAction": {
		    "@type": "SearchAction",
		    "target": "https://www.randomhome.net/home={search_term_int}",
		    "query-input": "required name=search_term_int"
		  }
		}
		</script>
	</head>
	<body>
		@include('seo')
		<div id="overlay">
			<div id="loader" class="loader"></div>
		</div>
		<div class="container">
			<div class="sidebar">
				<div class="sidebar-icon">
						<a href="/" class="sidebar-tab"><img src="favicon.ico" alt="Menu Icon"><h1>Discover Random Homes</h1></a>
					<svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" onclick="dropDown(this)" class="bars" viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><style>svg{fill:#ffffff}</style><path d="M0 96C0 78.3 14.3 64 32 64H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32H416c17.7 0 32 14.3 32 32s-14.3 32-32 32H32c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32H32c-17.7 0-32-14.3-32-32s14.3-32 32-32H416c17.7 0 32 14.3 32 32z"/></svg>
				</div>
				<hr>
				<div class="menu-wrapper">
					<nav class="menu">
						<ul>
							<?php
							$homesArray = array_reverse( Session::get('homes') ?? [], true);
							if( count( $homesArray ) != 0 )
							{
								foreach( $homesArray as $key => $home )
								{
									$prevHome = $home->address;
									if( $key === array_key_first( $homesArray ) )
									{
										$prevHome = '<b><u>Most Recent</u></b><br> ' . $prevHome;
									}
									?>
									<li class="{{$home->type}}Color">
										<a href="/results?home={{$key}}">{!! $prevHome !!}</a>
									</li>
									<?php
								}
							}
							else
							{
								?>
								<h2>No Recent Results</h2>
								<?php
							}
							?>
						</ul>
					</nav>
				</div>
			</div>
			<div class="screen-wrap">
				
				<?php
				if( empty( $results->url ) )
				{
					?>
					@include( 'homeFilters', ['states' => $states] )
					<?php
				}
				else
				{
					?>
					@include( 'homeResults', ['results' => $results] )
					<?php
				}
				?>
			</div>
		</div>
		<footer>
			<p>
				<a href="https://www.youtube.com/channel/UCZt2_bOh88F4awVYjC4EGxA" target="_blank"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 461.001 461.001" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path style="fill:#F61C0D;" d="M365.257,67.393H95.744C42.866,67.393,0,110.259,0,163.137v134.728 c0,52.878,42.866,95.744,95.744,95.744h269.513c52.878,0,95.744-42.866,95.744-95.744V163.137 C461.001,110.259,418.135,67.393,365.257,67.393z M300.506,237.056l-126.06,60.123c-3.359,1.602-7.239-0.847-7.239-4.568V168.607 c0-3.774,3.982-6.22,7.348-4.514l126.06,63.881C304.363,229.873,304.298,235.248,300.506,237.056z"></path> </g> </g></svg></a>
				<a href="https://twitter.com/RandomHomes" target="_blank"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 410.155 410.155" xml:space="preserve" fill="#000000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path style="fill:#76A9EA;" d="M403.632,74.18c-9.113,4.041-18.573,7.229-28.28,9.537c10.696-10.164,18.738-22.877,23.275-37.067 l0,0c1.295-4.051-3.105-7.554-6.763-5.385l0,0c-13.504,8.01-28.05,14.019-43.235,17.862c-0.881,0.223-1.79,0.336-2.702,0.336 c-2.766,0-5.455-1.027-7.57-2.891c-16.156-14.239-36.935-22.081-58.508-22.081c-9.335,0-18.76,1.455-28.014,4.325 c-28.672,8.893-50.795,32.544-57.736,61.724c-2.604,10.945-3.309,21.9-2.097,32.56c0.139,1.225-0.44,2.08-0.797,2.481 c-0.627,0.703-1.516,1.106-2.439,1.106c-0.103,0-0.209-0.005-0.314-0.015c-62.762-5.831-119.358-36.068-159.363-85.14l0,0 c-2.04-2.503-5.952-2.196-7.578,0.593l0,0C13.677,65.565,9.537,80.937,9.537,96.579c0,23.972,9.631,46.563,26.36,63.032 c-7.035-1.668-13.844-4.295-20.169-7.808l0,0c-3.06-1.7-6.825,0.485-6.868,3.985l0,0c-0.438,35.612,20.412,67.3,51.646,81.569 c-0.629,0.015-1.258,0.022-1.888,0.022c-4.951,0-9.964-0.478-14.898-1.421l0,0c-3.446-0.658-6.341,2.611-5.271,5.952l0,0 c10.138,31.651,37.39,54.981,70.002,60.278c-27.066,18.169-58.585,27.753-91.39,27.753l-10.227-0.006 c-3.151,0-5.816,2.054-6.619,5.106c-0.791,3.006,0.666,6.177,3.353,7.74c36.966,21.513,79.131,32.883,121.955,32.883 c37.485,0,72.549-7.439,104.219-22.109c29.033-13.449,54.689-32.674,76.255-57.141c20.09-22.792,35.8-49.103,46.692-78.201 c10.383-27.737,15.871-57.333,15.871-85.589v-1.346c-0.001-4.537,2.051-8.806,5.631-11.712c13.585-11.03,25.415-24.014,35.16-38.591 l0,0C411.924,77.126,407.866,72.302,403.632,74.18L403.632,74.18z"></path> </g></svg></a>
				<br>
				&copy; RandomHome.net <?php echo date('Y'); ?><br>
				Thank you to <a href="https://www.zillow.com" target="_blank"><b>Zillow</b></a> for their services.<br>
				This project was started by a web developer who shares a passion for finding random places and wanted to create a platform to share that experience with everyone else.
			</p>
		</footer>
		<script>
	        window.onload = function() {
	            // Check if some condition is met (e.g., a failed request)
	            var conditionFailed = true; // Replace this with your actual condition

	            if( {{ !empty( request('fail') ) }} )
	            {
	                alert('Unfortunately, no properties matching your search criteria were found. Please broaden your filters for better results.');
	            }
	        };
	    </script>
		<script>
			function newSrc( value ) {
				$('#type').val( value );
				$('button').prop('disabled', true).addClass('clicked');
				var loader = $('#loader');
				loader.show();

				$('#min-price, #max-input, #min-rent-price, #max-rent-input').val(function(_, value)
				{
					return value.replace(/,/g, '');
				});
				
				
				$('form').submit();
			};

			function dropDown( e )
			{
				e.classList.toggle("pressed");
				$('.menu-wrapper').toggle();
			}

			$(window).resize(function() {
		        if ($(window).width() > 750) {
           			$('.menu-wrapper').show();
		        }
		        else
		        {
		        	$('.bars').removeClass("pressed");
		        	$('.menu-wrapper').hide();
		        }
		    });
		</script>
		<script>
			 $(document).ready(function() {
				 if( '{{Session::get('type') ?? ''}}' == 'ForRent' )
				 {
					 $('#sale-filters, #rent-filters, #submit-rent, #submit-sale').toggle();
				 }
			 });
			 $('.tab').click(function() {
				if ($(this).hasClass('tab-clicked')) {
				  return false; // Prevent the click event
				}

				$('.tab').not(this).removeClass('tab-clicked'); // Remove .clicked class from other tabs 
				$(this).addClass('tab-clicked'); // Add .clicked class to the clicked tab
				
				$('#sale-filters, #rent-filters, #submit-rent, #submit-sale').toggle();
			  });
			// Get all number input fields by class name
			const numberInputs = document.querySelectorAll('.number-input');

			// Add event listener to each number input field
			numberInputs.forEach(input => {
			  input.addEventListener('input', function() {
				formatNumberInput(this);
			  });

			  input.addEventListener('focusout', function() {

			        var inputName = this.getAttribute('name');
			        var value = parseInt( this.value.replace(/,/g, '').replace(/[^0-9]/g, '') );

			        switch (inputName) {
			            case 'minrp':
			            	var maxValue = parseInt( $('input[name="maxrp"]').val().replace(/,/g, '').replace(/[^0-9]/g, '') );
			                if( value >= maxValue )
			                {
			                	$('input[name="maxrp"]').val('');
			                }
			                break;
			            case 'maxrp':
			            	var minValue = parseInt( $('input[name="minrp"]').val().replace(/,/g, '').replace(/[^0-9]/g, '') );
			                if( minValue.length != 0 && value <= minValue)
			                {
			                	$('input[name="minrp"]').val('');
			                }
			                break;
			            case 'minsp':
			            	var maxValue = parseInt( $('input[name="maxsp"]').val().replace(/,/g, '').replace(/[^0-9]/g, '') );
			                if( value >= maxValue )
			                {
			                	$('input[name="maxsp"]').val('');
			                }
			                break;
			            case 'maxsp':
			                var minValue = parseInt( $('input[name="minsp"]').val().replace(/,/g, '').replace(/[^0-9]/g, '') );
			                if( minValue.length != 0 && value <= minValue)
			                {
			                	$('input[name="minsp"]').val('');
			                }
			                break;
			        }
			    });
			});



			function formatNumberInput(input) {
			  // Remove existing commas and non-numeric characters
			  let value = input.value.replace(/,/g, '').replace(/[^0-9]/g, '');
			  
			  // Format the value with commas
			  value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
			  
			  // Set the formatted value back to the input
			  input.value = value;
			}
		</script>
		<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5564950322081391"
     		crossorigin="anonymous"></script>
	</body>
</html>
