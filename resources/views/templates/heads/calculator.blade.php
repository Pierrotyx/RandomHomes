<title>Random Homes | Mortgage Calculator</title>
 <!-- Meta tags for SEO -->
<meta name="description" content="Experience seamless mortgage calculations with our automated Mortgage Calculator. It effortlessly inputs property details from your discovered homes, providing instant monthly payment estimates. Take the guesswork out of your home purchase planning!">
<meta name="keywords" content="random homes, Mortgage calculator, Salary estimator, Home investment planner">
			<!-- Open Graph meta tags -->
<meta property="og:title" content="Random Homes Mortgage Calculator">
<meta
	property="og:description"
	content="Experience seamless mortgage calculations with our automated Mortgage Calculator. It effortlessly inputs property details from your discovered homes, providing instant monthly payment estimates. Take the guesswork out of your home purchase planning!"
>
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset( '/assets/img/calculator.png' ) }}">
<meta property="og:image:alt" content="Random Homes Mortgage Calculator">

<!-- Twitter Card meta tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Random Homes Mortgage Calculator">
	<meta
		name="twitter:description"
		content="Experience seamless mortgage calculations with our automated Mortgage Calculator. It effortlessly inputs property details from your discovered homes, providing instant monthly payment estimates. Take the guesswork out of your home purchase planning!"
	>
<meta name="twitter:image" content="{{ asset( '/assets/img/calculator.png' ) }}">
<meta name="twitter:image:alt" content="Random Homes Mortgage Calculator">

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "WebSite",
  "name": "Random Homes Mortgage Calculator",
  "description": "Experience seamless mortgage calculations with our automated Mortgage Calculator. It effortlessly inputs property details from your discovered homes, providing instant monthly payment estimates. Take the guesswork out of your home purchase planning!",
  "url": "{{ url()->current() }}"
  "potentialAction": {
	"@type": "Place",
	"target": "{{ url()->current() }}?home={search_term_int}",
	"query-input": "required name=search_term_int"
  }
}
</script>