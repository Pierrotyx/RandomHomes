<div class="info-box compress">
	<div class="filter-wrapper" style="height:inherit;">
		<a href="/placethatprice">
			<button id="game-button" class="submit-button">
				<span>Play Now!</span>
			</button>
		</a>
		<div class="tabs-container">
		  <div class="tab placeLeader">3<br>Homes</div>
		  <div class="tab placeLeader tab-clicked">5<br>Homes</div>
		  <div class="tab placeLeader">10<br>Homes</div>
		  <div class="tab placeLeader">100<br>Homes</div>
		</div>
		<div class="leaderboard">
			<div class="leaderboard-header">
				<div class="player">
					<h2>Place That Price Top 500</h2>
					<div class="interval">
						<button onclick="changeInterval( this )" disabled>Day</button>
						<button onclick="changeInterval( this )">Week</button>
						<button onclick="changeInterval( this )">Month</button>
						<button onclick="changeInterval( this )">Year</button>
						<button onclick="changeInterval( this )">All</button>
					</div>
					<br>
				</div>
			</div>
			<div class="leaderboard-header">
				<div class="rank">Rank</div>
				<div class="player">Player</div>
				<div class="score">Score</div>
				<div class="score">Time</div>
			</div>
			<div id="board">
				@include( 'templates.leaderboard', [ 'boardInfo' => $boardInfo ] )
			</div>
		</div>
		<a href="/placethatprice">
			<button id="game-button" class="submit-button">
				<span>Play Now!</span>
			</button>
		</a>
	</div>
</div>
<?php