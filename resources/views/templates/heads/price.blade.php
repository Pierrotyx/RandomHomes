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