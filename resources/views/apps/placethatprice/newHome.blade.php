<div class="info-box stretch">
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
				<img id="preview-img" src="{{$results['imgSrc']}}" draggable="false"/>
				<br>
				<div class="tabs-container">
					<input
						type="text"
						id="propertyPrice"
						class="number-input"
						placeholder="Property Price?"
						oninput="formatNumberInput(this)"
					/>
					<br>
				</div>
					<small style="color:red; text-align:center">Must be greater than 0 and less then 10,000,000</small>
				<input type="hidden" value="{{$id}}" id="propertyId">
				<table>
					<tr>
						<td>
							<b>Location:</b>
							<br>
							{{ !empty( $results['city'] ) ? $results['city']: 'Unknown' }},
							<br>
							{{ !empty( $results['state'] ) ? $results['state']: 'Unknown' }}
						</td>
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
<div class="timer-container">
	<div class="timer" id="countdown">25</div>
</div>

<script>
	var countdownElement = document.getElementById('countdown');
	var countdown = 25;
	var clickOff = false;

	function updateCountdown() {
			countdown--;
			countdownElement.textContent = countdown;

			if( countdown === 0 )
			{
				countdownElement.textContent = "Time's up!";
				clearInterval(timerInterval);
				checkResults( true );
			}
	}

	var timerInterval = setInterval(updateCountdown, 1000);
	var imgElement = document.getElementById("preview-img");

    // Prevent right-click context menu on the image
    imgElement.addEventListener("contextmenu", function (e) {
		e.preventDefault();
	});

	/*
	window.addEventListener('blur', function ()
	{
		if( !clickOff )
		{
			countdown = 1;
			clickOff = true;
			alert('If you were cheating, stop! If you clicked the wrong button, I\'m Sorry.');
		}
	});
	*/
</script>