<div class="info-box compress">
	<div class="filter-wrapper" style="height:inherit;">
		<a href="/placethatprice">
			<button id="game-button" class="submit-button">
				<span>Play Now!</span>
			</button>
		</a>
		<div class="leaderboard">
			<div class="leaderboard-header">
				<div class="player"><h2>Place That Price Top 500</h2></div>
			</div>
			<div class="leaderboard-header">
				<div class="rank">Rank</div>
				<div class="player">Player</div>
				<div class="score">Score</div>
			</div>
			<?php
			$i = 1;
			foreach( $boardInfo as $info )
			{
				$turn = ( $turn ?? '' ) == 'even' ? 'odd' : 'even';
				?>
				<div class="leaderboard-row {{$turn}}">
					<div class="rank">{{number_format( $i )}}</div>
					<div class="player">{{$info->name}}</div>
					<div class="score">{{number_format( $info->score )}}</div>
				</div>
				<?php
				$i++;
			}
			?>
		</div>
		<a href="/placethatprice">
			<button id="game-button" class="submit-button">
				<span>Play Now!</span>
			</button>
		</a>
	</div>
</div>
<?php