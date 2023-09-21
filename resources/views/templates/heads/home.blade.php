<title>Random Homes | {{$results->address ?? 'RDiscovered Home'}}</title>
 <!-- Meta tags for SEO -->
<meta name="description" content="Check out this amazing property! Price: {{$results->price ?? '--'}}, Beds: {{$results->bedrooms ?? '--'}}, Baths: {{$results->bathrooms ?? '--'}}, Living Size: {{$results->livingSize ?? '--'}}, Lot Size: {{$results->lotAreaSize ?? '--'}}">
<meta name="keywords" content="random homes, searching for properties, houses for houses, us houses">
<!-- Open Graph meta tags -->
<meta property="og:title" content="{{$results->address ?? 'Random Home'}}">
<meta
	property="og:description"
	content="Check out this amazing property! Price: {{$results->price ?? '--'}}, Beds: {{$results->bedrooms ?? '--'}}, Baths: {{$results->bathrooms ?? '--'}}, Living Size: {{$results->livingSize ?? '--'}}, Lot Size: {{$results->lotAreaSize ?? '--'}}"
>
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ $results->imgSrc ?? asset( '/assets/img/DiceHouse.png' ) }}">
<meta property="og:image:alt" content="Random Home Preview">

<!-- Twitter Card meta tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{$results->address ?? 'Random Home'}}">
	<meta
		name="twitter:description"
		content="Check out this amazing property! Price: {{$results->price ?? '--'}}, Beds: {{$results->bedrooms ?? '--'}}, Baths: {{$results->bathrooms ?? '--'}}, Living Size: {{$results->livingSize ?? '--'}}"
	>
<meta name="twitter:image" content="{{ $results->imgSrc ?? asset( '/assets/img/DiceHouse.png' ) }}">
<meta name="twitter:image:alt" content="Random Home Preview">

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "WebSite",
  "name": "Random Home Finder",
  "description": "Check out this amazing property! Price: {{$results->price ?? '--'}}, Beds: {{$results->bedrooms ?? '--'}}, Baths: {{$results->bathrooms ?? '--'}}, Living Size: {{$results->livingSize ?? '--'}}",
  "url": "{{ url()->current() }}"
}
</script>