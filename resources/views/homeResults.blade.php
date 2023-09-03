<div class="info-box">
	<div class="filter-wrapper">
		<h2 class="title">Results</h2>
		<hr />
		<div id="result-body">
			<?php
			if( empty( $results->url ) )
			{
				?>
				<h3>Failure</h3>
				<?php
			}
			else
			{
				?>
				<a href="{{$results->url}}" target="_blank" class="result-link">
					<img id="preview-img" src="{{$results->imgSrc}}" />
					<br>
					{{$results->address}}
				</a>
				<br>
				<?php
				if( $units )
				{
					?>
					<table>
						<?php
						$i = 0;
						foreach( $units as $unit )
						{
							?>
							<tr>
								<td><b>Unit #{{++$i}}</b></td>
								<td>{{$unit->price}}</td>
								<td>{{$unit->beds}} Bedrooms</td>
							</tr>
							<?php
						}
						?>
					</table>
					<?php
				}
				else
				{
					?>
					<table>
						<tr>
							<td><b>Property Price:</b><br>{{!empty( $results->price ) ? ( '$' . number_format( $results->price ) ) : '--'}}</td>
							<td><b>Property Type:</b><br>{{!empty( $results->propertyType ) ? $results->propertyType : '--'}}</td>
						</tr>
						<tr>
							<td><b>Bedrooms:</b><br>{{!empty( $results->bedrooms ) ? $results->bedrooms : '--'}}</td>
							<td><b>Bathrooms:</b><br>{{!empty( $results->bathrooms ) ? $results->bathrooms : '--'}}</td>
						</tr>
						<tr>
							<td><b>Living Size:</b><br>{{$results->livingSize}}</td>
							<td><b>Lot Size:</b><br>{{$results->lotAreaSize}}</td>
						</tr>
					</table>
					<br>
					<?php
				}
			}
			?>
		</div>
		<?php
		if( $filters )
		{
			?>
			<div class="side-buttons">
				<a href="/"><button id="return-button" class="submit-button"><span>New Home</span></button></a>
				<form method="POST" action="/reroll" class="side-buttons">
				@csrf <!-- {{ csrf_field() }} -->
					<input type="hidden" name="type" id="type" value="{{$id}}" />
					<button id="reroll-button" class="submit-button">
						<span>Reroll</span>
					</button>
				</form>
			</div>
			<?php
		}
		else
		{
			?>
			<a href="/"><button id="return-button" class="submit-button"><span>New Home</span></button></a>
			<?php
		}
		?>
	</div>
</div>

<script>
	var csrfToken = $('meta[name="csrf-token"]').attr('content');
	$('.result-link').click( function( event )
	{
    	$.ajax({
			url: '/clicked-tab',
			type: 'POST',
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
			},
			data: {
		        id: {{$results->id}}
		    }
		});
	});
</script>