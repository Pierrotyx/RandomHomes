<div class="info-box">
	<div class="filter-wrapper">
		<div class="score-screen">
			<h2 class="title" style="margin-bottom: 0;"><u>Final Score</u></h2>
			<hr />
			<h2>Congrats! You are ranked #{{$rank}}!</h2>
			<div class="gameSubmit">
				<input type="text" max="12" placeholder="Enter Name" id="game-name"/>
				<input type="submit" onclick="changeName()" value="Done" class="gameSubmit"/>
			</div>
			<div id="result-body">
				<h1 class="points" style="margin: 0;"><br>{{number_format( ceil( Session::get( 'score' ) ) )}} POINTS!</h1>
				<h2 style="margin-top: 0;">/ 50,000 Points</h2>
				<div class="result-grid">
					<?php
					foreach( Session::get( 'gameInfo' ) as $property )
					{
						?>
						<a href="{{$property['url']}}" target="_blank" class="gameResults">
							<img src="{{$property['img']}}" />
							<h3>{{number_format( $property['score'] )}} Points</h3>
						</a>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<a href="/placethatprice">
			<button id="game-button" class="submit-button">
				<span>New Game?</span>
			</button>
		</a>
		<a href="/placethatprice/leaderboard">
			<button id="leaderboard-button" class="submit-button">
				<span>Leaderboard</span>
			</button>
		</a>
		<br>
	</div>
</div>