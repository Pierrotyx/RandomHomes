<div class="info-box">
	<div class="filter-wrapper">
		<h2 class="title">Place that Price</h2>
		<hr />
		<div id="result-body">
			<p>
				Are you a travel expert with a keen eye for value? Prepare to put your skills to the test in the ultimate guessing game – "Place that Price"!
				<br><br>
				How It Works:<br>
				&emsp;🌍 We'll present you with an image and details about destinations from around the US.<br>
				&emsp;💰 Your challenge? Guess the price of each place as accurately as possible.<br>
				&emsp;🎯 The closer your guess, the more points you'll earn!<br>
				&emsp;⏰ You have 20 seconds to guess your answer!<br>
				&emsp;🏆 Compete to obtain the highest score on the leadboard!
				<br><br>
				Embark on a journey of discovery and fun as you test your travel-price intuition. Let the adventure begin!
			</p>
			<h3 style="color:purple"><a href="https://www.reddit.com/r/RandomHomes/">Click here to join our reddit page to give your thoughts and opinions!</a></h3>
		</div>
		<div class="list-grid">
			<button id="game-button" onclick="levelCount = 3; newLevel()" class="submit-button"><span>3 Homes</span></button>
			<button id="game-button" onclick="levelCount = 5; newLevel()" class="submit-button"><span>5 Homes</span></button>
			<button id="game-button" onclick="levelCount = 10; newLevel()" class="submit-button"><span>10 Homes</span></button>
			<button
				id="game-button"
				onclick="
					levelCount = 100;
					alert('This is about skill and endurance. Take breaks in between rounds if needed. Good luck Challenger!');
					newLevel()" class="submit-button"
			><span>100 Homes</span></button>
		</div>
		<a href="/placethatprice/leaderboard">
			<button id="leaderboard-button" class="submit-button">
				<span>Leaderboard</span>
			</button>
		</a>
	</div>
</div>
