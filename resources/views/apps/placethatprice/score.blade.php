<div class="info-box">
	<div class="filter-wrapper">
		<div class="score-screen">
			<h2 class="title">Score {{$round}}</h2>
			<hr />
			<div id="result-body">
				<h1 class="points">{{number_format( $info['score'] )}} POINTS!</h1>
				<h2>Your Price: {{number_format( $guess )}}</h2>
				<h2>Actual Price: {{number_format( $info['price'] )}}</h2>
				<h1 class="total">Total Score: {{number_format( $total )}}</h1>
				<h1><a href="{{$info['url']}}" target="_blank">Click Here to view {{ $info['address'] }}</a></h1>
			</div>
		</div>
		<button id="game-button" onclick="newLevel()" class="submit-button">
			<span>{{$round == 5 ? 'View Results!' : 'Next Level!'}}</span>
		</button>
	</div>
</div>