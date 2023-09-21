<div class="info-box stretch">
	<div class="filter-wrapper">
		<h2 class="title">{{ ( $results->type == 'ForRent' ? 'Rent' : 'Sale' ) }} Results</h2>
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
					$propName = $results->type == 'ForRent' ? 'Rental' : 'Sale';
					?>
					<table>
						<tr>
							<td>
								<div class="resultLink">
									<?php
									if( $results->type == 'ForSale' )
									{
										?>
										<a href="/calculator/{{str_replace( ' ', '-', strtolower( $state ) )}}?home={{$id}}" target="_blank" class="result-link">
											<svg xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" style="fill:#E64A19" viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M64 0C28.7 0 0 28.7 0 64V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H64zM96 64H288c17.7 0 32 14.3 32 32v32c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V96c0-17.7 14.3-32 32-32zm32 160a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zM96 352a32 32 0 1 1 0-64 32 32 0 1 1 0 64zM64 416c0-17.7 14.3-32 32-32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H96c-17.7 0-32-14.3-32-32zM192 256a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm32 64a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zm64-64a32 32 0 1 1 0-64 32 32 0 1 1 0 64zm32 64a32 32 0 1 1 -64 0 32 32 0 1 1 64 0zM288 448a32 32 0 1 1 0-64 32 32 0 1 1 0 64z"/></svg>
										</a>
										<?php
									}
									?>
									<div>
										<b>{{ $propName }} Price:</b>
										<br>
										{{!empty( $results->price ) ? ( '$' . number_format( $results->price ) ) : '--'}}
									</div>
								</div>
							</td>
							<td><b>{{ $propName }} Type:</b><br>{{!empty( $results->propertyType ) ? $results->propertyType : '--'}}</td>
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
				<form method="POST" action="/reroll{{!empty( request()->page ) ? ( '?page=' . request()->page ) : ''}}" class="side-buttons">
				@csrf <!-- {{ csrf_field() }} -->
					<input type="hidden" name="type" id="type" value="{{$id}}" />
					<button id="reroll-button" class="submit-button">
						<span>Reroll</span>
					</button>
				</form>
				<?php
				$previous = url()->previous();
				$path = explode( '/', url()->previous() );
				if( !empty( request()->page ) )
				{
					?>
					<a href="/results?page={{request()->page}}"><button id="return-button" class="submit-button"><span>All Homes</span></button></a>
					<?php
				}
				else
				{
					?>
					<a href="/"><button id="return-button" class="submit-button"><span>Change Filters</span></button></a>
					<?php
				}
				?>
			</div>
			<?php
		}
		else
		{
			?>
			<a href="/"><button id="return-button" class="submit-button"><span>Change Filters</span></button></a>
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