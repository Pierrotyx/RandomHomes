<div class="info-box">
	<div class="filter-wrapper">
		<h2 class="title">Place that Price - Competitive</h2>
		<hr />
		<a href="/placethatprice" class="playLinks">Go Back</a>
		<a href="#" class="playLinks" id="openModal">How to play</a>
		<!-- The modal -->
		<div id="myModal" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<span class="close" id="closeModal">&times;</span>
				<h2>Place That Price How To</h2>
				<p>
					Are you a travel expert with a keen eye for value? Prepare to put your skills to the test in the ultimate guessing game – "Place that Price"!
					<br><br>
					How It Works:<br>
					&emsp;🌍 We'll present you with an image and details about destinations from around the US.<br>
					&emsp;💰 Your challenge? Guess the price of each place as accurately as possible.<br>
					&emsp;🎯 The closer your guess, the more points you'll earn!<br>
					&emsp;⏰ You have 25 seconds to guess your answer!<br>
					&emsp;🏆 Compete to obtain the highest score on the leadboard!
					<br><br>
					Embark on a journey of discovery and fun as you test your travel-price intuition. Let the adventure begin!
				</p>
			</div>
		</div>
		<br>
		<div class="list-grid">
			<button id="game-button" onclick="levelCount = 3; newLevel( '/new-home' )" class="submit-button"><span>3 Homes</span></button>
			<button id="game-button" onclick="levelCount = 5; newLevel( '/new-home' )" class="submit-button"><span>5 Homes</span></button>
			<button id="game-button" onclick="levelCount = 10; newLevel( '/new-home' )" class="submit-button"><span>10 Homes</span></button>
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
		<a href="https://www.reddit.com/r/RandomHomes/" style="text-decoration-color: #FF5700;">
			<h3 style="color:#FF5700; text-align: center; font-size: 150%; background-color: white;">Join our subreddit here to give your thoughts and opinions!</h3>
		</a>
	</div>
</div>

<script>
    // Get references to the modal and its close button
	var levelCount = 0;
    var modal = document.getElementById('myModal');
    var closeModal = document.getElementById('closeModal');

    // Get a reference to the "How to place" element
    var openModal = document.getElementById('openModal');
	var homeDay = 0;

    // When "How to place" is clicked, show the modal
    openModal.onclick = function () {
        modal.style.display = 'block';
    }

    // When the close button is clicked, hide the modal
    closeModal.onclick = function () {
        modal.style.display = 'none';
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>
