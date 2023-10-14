<div class="info-box">
	<div class="filter-wrapper">
		<div class="score-screen">
			<h2 class="title" style="margin-bottom: 0;"><u>Final Score</u></h2>
			<hr />
			<h2>
			<?php
			if( count( $ranks ) != 0 )
			{
				echo 'Congrats!!!';
				foreach( $ranks as $key => $rank )
				{
					echo '<br>Ranked #' . $rank . ' in a ' . $key . '!';
				}
			}
			?>
			</h2>
			<?php
			if( !isset( $dailyValues ) )
			{
				?>
				<div class="gameSubmit">
					<input type="text" max="12" placeholder="Enter Name" id="game-name"/>
					<input type="submit" onclick="changeName()" value="Done" class="gameSubmit"/>
				</div>
				<?php
			}
			?>
			<div id="result-body">
				<h1 class="points" style="margin: 0;"><br>{{number_format( ceil( $total ) )}} POINTS!</h1>
				<h2 style="margin-top: 0;">/ {{number_format( $level * 10000 ) }} Points</h2>
				<div class="result-grid">
					<?php
					if( isset( $dailyValues ) )
					{
						foreach( $dailyValues as $property )
						{
							?>
							<a href="{{$property->url}}" target="_blank" class="gameResults">
								<img src="{{$property->imgSrc}}" />
								<h3>{{$property->score}} Points</h3>
							</a>
							<?php
						}
					}
					else
					{
						foreach( Session::get( 'gameInfo' ) as $property )
						{
							?>
							<a href="{{$property['url']}}" target="_blank" class="gameResults">
								<img src="{{$property['img']}}" />
								<h3>{{number_format( $property['score'] )}} Points</h3>
							</a>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php
		if( !isset( $dailyValues ) )
		{
			?>
			<a href="/placethatprice/competitive">
				<button id="game-button" class="submit-button">
					<span>New Game?</span>
				</button>
			</a>
			<a href="/placethatprice/leaderboard">
				<button id="leaderboard-button" class="submit-button">
					<span>Leaderboard</span>
				</button>
			</a>
			<?php
		}
		else
		{
			?>
			<a href="/placethatprice/daily">
				<button id="game-button" class="submit-button">
					<span>Back to Daily Homes</span>
				</button>
			</a>
			<?php
		}
		?>
		<a href="/placethatprice">
				<button id="return-button" class="submit-button">
					<span>Go Home</span>
				</button>
			</a>
		<br>
	</div>
</div>
<script>
	$(document).ready(function() {
		if( $('#game-name').length == 0 )
		{
			gameStarted = false;
		}
	});
</script>