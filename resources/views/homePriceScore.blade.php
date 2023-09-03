<div class="info-box">
	<div class="filter-wrapper">
		<h2 class="title">Score {{$round}}</h2>
		<hr />
		<div id="result-body">
			<h1>{{$points}} POINTS!</h1>
			<br>
			<h2>Your Price: {{$guess}}</h2>
			<h2>Actual Price: {{$property->price}}</h2>
			<br>
			<br>
			<h1>Total Score: {{$total}}</h1>
		</div>
		<button id="game-button" onclick="newLevel()" class="submit-button">
			<span>{{$round == 5 ? 'View Results!' : 'Next Level!'}}</span>
		</button>
	</div>
</div>