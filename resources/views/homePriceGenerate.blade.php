<div class="info-box">
	<div class="filter-wrapper">
		<h2 class="title">Round {{$round}}</h2>
		<hr />
		<div id="result-body">
			<?php
			if( empty( $results['url'] ) )
			{
				?>
				<h3>Failure</h3>
				<?php
			}
			else
			{
				?>
				<img id="preview-img" src="{{$results['imgSrc']}}" />
				<br>
				<input
					type="text"
					id="propertyPrice"
					class="number-input"
					placeholder="Propery Price?"
					oninput="formatNumberInput(this)"
				/>
				<input type="hidden" value="{{$results['id']}}" id="propertyId">
				<br>
				<table>
					<tr>
						<td><b>State:</b><br>{{ !empty( $results['state'] ) ? $results['state']->state: 'Unknown' }} </td>
						<td><b>Property Type:</b><br>{{!empty( $results['propertyType'] ) ? $results['propertyType'] : '--'}}</td>
					</tr>
					<tr>
						<td><b>Bedrooms:</b><br>{{!empty( $results['bedrooms'] ) ? $results['bedrooms'] : '--'}}</td>
						<td><b>Bathrooms:</b><br>{{!empty( $results['bathrooms'] ) ? $results['bathrooms'] : '--'}}</td>
					</tr>
					<tr>
						<td><b>Living Size:</b><br>{{$results['livingSize']}}</td>
						<td><b>Lot Size:</b><br>{{$results['lotAreaSize']}}</td>
					</tr>
				</table>
				<br>

				<?php
			}
			?>
		</div>
		<button id="game-button" onclick="checkResults()" class="submit-button"><span>Make Your Guess</span></button>
	</div>
</div>