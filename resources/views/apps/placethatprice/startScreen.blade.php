<div class="info-box">
	<div class="filter-wrapper">
		<h2 class="title">Place that Price</h2>
		<hr />
		<a href="#" class="playLinks" id="openModal">What are these modes?</a>
		<div id="myModal" class="modal">
			<div class="modal-content">
				<span class="close" id="closeModal">&times;</span>
				<h2>What are they modes?</h2>
				<div class="game-mode">
					<h2>Daily Homes 🌞</h2>
					<p>
						<strong>Fresh Challenge Every Day:</strong> Experience a brand new set of homes to guess the prices of, refreshed daily. Whether you're a seasoned player or just starting out, the daily challenge keeps things exciting and engaging.
					</p>
					<p>
						<strong>Share Score on Social:</strong> Take pride in your achievements and share your daily score with your friends, family, and the global community.
					</p>
				</div>

				<div class="game-mode">
					<h2>Competitive Play 🏆</h2>
					<p>
						<strong>Varied Modes:</strong> Test your skills with randomly generated batches of homes. Choose from 3, 5, 10, or go all in with 100 homes at once.
					</p>
					<p>
						<strong>Leaderboard Glory:</strong> Compete against players from around the world and earn your spot on the leaderboard. Can you make it to the top? Compare your scores and strive for the highest rank.
					</p>
				</div>

				<div class="game-mode">
					<h2>Custom Mode ⚙️</h2>
					<p>
						<strong>Tailored to Your Preferences:</strong> Customize the game to match your preferences. Adjust the settings for randomness, including the price range, specific states or regions, and even the types of properties you want to guess.
					</p>
					<p>
						<strong>Play with Friends and Family:</strong> Generate a unique code to invite your friends and family to join in the fun. Share your customized game settings with them, and see who can guess the closest prices in your personally curated challenge.
					</p>
				</div>
			</div>
		</div>
		<br>
		<h2>Time Until New Daily: <span id="countdown"></span></h2>
		<a href="/placethatprice/daily">
			<button id="game-button" class="submit-button"><span>Daily Homes</span></button>
		</a>
		<a href="/placethatprice/competitive">
			<button id="game-button" class="submit-button"><span>Competitive Play</span></button>
		</a>
			<button id="game-button" class="submit-button" style="background-color: #ccc; pointer-events: none;" disabled><span>Custom Mode - Coming soon!</span></button>
		<a href="https://www.reddit.com/r/RandomHomes/" style="text-decoration-color: #FF5700;">
			<h3 style="color:#FF5700; text-align: center; font-size: 150%; background-color: white;">Join our subreddit here to give your thoughts and opinions!</h3>
		</a>
	</div>
</div>

<script>
    // Get references to the modal and its close button
    var modal = document.getElementById('myModal');
    var closeModal = document.getElementById('closeModal');

    // Get a reference to the "How to place" element
    var openModal = document.getElementById('openModal');

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
	
	function startCountdown() {
		// Target time (7:00 AM UTC)
		const targetTime = new Date();
		targetTime.setUTCHours(7, 0, 0, 0);

		function updateCountdown() {
			const currentTime = new Date();
			const timeDifference = targetTime - currentTime;

			if (timeDifference <= 0) {
				// The target time has already passed today, so set it for tomorrow
				targetTime.setUTCDate(targetTime.getUTCDate() + 1);
				updateCountdown();
			} else {
				const hours = Math.floor((timeDifference / (1000 * 60 * 60)) % 24);
				const minutes = Math.floor((timeDifference / (1000 * 60)) % 60);
				const seconds = Math.floor((timeDifference / 1000) % 60);

				const countdownElement = document.getElementById("countdown");
				countdownElement.textContent = `${hours}h ${minutes}m ${seconds}s`;

				setTimeout(updateCountdown, 1000); // Update every 1 second
			}
		}

		updateCountdown();
	}

	// Call the function to start the countdown
	startCountdown();
</script>
