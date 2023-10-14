<div class="info-box">
	<div class="filter-wrapper">
		<h2 class="title">Place that Price - Daily</h2>
		<hr />
		<a href="/placethatprice" class="playLinks">Go Back</a>
		<a href="#" class="playLinks" id="openModal">How to play</a>
		<!-- The modal -->
		<div id="myModal" class="modal">
			<!-- Modal content -->
			<div class="modal-content">
				<span class="close" id="closeModal">&times;</span>
				<h2>Place That Price Daily</h2>
				<p>
					Introducing the Daily Travel Challenge! Every day, three exciting types of adventures await you - exploring 3, 5, and 10 different homes.
					Once you start a home, you will not be able to return. Don't worry; we'll keep track of your progress, so you can take a breather in between rounds and pick up right where you left off.
					Plus, there's no need to fret about leaderboards; this is all about challenging your friends, family, and yourself!
					<br><br>
					🌍 <b>Explore the World</b>:
					Get ready to become a travel expert with an eye for value. In "Place that Price," we'll present you with captivating images and intriguing details about destinations from across the US.<br>

					💰 Guess the Price:
					Your mission? Guess the price of each place as accurately as possible. The closer your guess, the more points you'll earn, and the closer you are to becoming a travel whiz.<br>

					🎯 Take Your Time:
					You've got 25 seconds to make your best guess. Will you get it right?<br>

					🏆 Challenge Friends and Family:
					Compete against your friends and family, not for a top spot on the leaderboard, but for the thrill of the game. Share your scores and see who's got the best travel-price intuition.<br><br>

					So, embark on this journey of discovery and fun, and let the adventure begin. Don't just travel the world; learn to "Place that Price"!
				</p>
			</div>
		</div>
		<div class="tabs-container" style="margin-top: 20px;">
		  <div class="tab placeDaily {{ ( Session::get('currentDaily') ?? '' ) == '3' ? 'tab-clicked' : '' }}">3<br>Homes</div>
		  <div class="tab placeDaily {{ ( Session::get('currentDaily') ?? '5' ) == '5' ? 'tab-clicked' : '' }}">5<br>Homes</div>
		  <div class="tab placeDaily {{ ( Session::get('currentDaily') ?? '' ) == '10' ? 'tab-clicked' : '' }}">10<br>Homes</div>
		</div>
		<div class="calendar">
			<div class="calendar-header">
				<span class="calendar-month" id="prevMonth">&#8592;</span>
				<span class="calendar-month" id="currentMonth">October 2023</span>
				<span class="calendar-month" id="nextMonth">&#8594;</span>
			</div>
			<div class="calendar-grid" id="calendarGrid"></div>
		</div>
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
</script>
<script>
	var levelCount = {{ Session::get('currentDaily') ?? 5 }};
	var homeDay = 0;
	const calendarGrid = document.getElementById("calendarGrid");
	const currentMonthElement = document.getElementById("currentMonth");
	const prevMonthButton = document.getElementById("prevMonth");
	const nextMonthButton = document.getElementById("nextMonth");

	let currentDate = new Date();

	function renderCalendar() {
		dayValues3 = @json( $scores['place3'] );
		dayValues5 = @json( $scores['place5'] );
		dayValues10 = @json( $scores['place10'] );

		const year = currentDate.getFullYear();
		const month = currentDate.getMonth();
		const daysInMonth = new Date(year, month + 1, 0).getDate();
		const currentDay = new Date().getDate();
		const oct = new Date("2023-10-01");
		const nov = new Date("2023-11-01");
		// Set the current month text
		currentMonthElement.textContent = new Date(year, month, 1).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

		// Clear the previous calendar
		calendarGrid.innerHTML = "";
		// Create the calendar grid
		for (let day = 1; day <= daysInMonth; day++) {
			const calendarDay = document.createElement("div");
			calendarDay.classList.add("calendar-day");
			calendarDay.textContent = day;
			const calendarDate = new Date(year, month, day);

            if( calendarDate <= new Date() )
			{
				calendarDay.classList.add("active");
				dayNum = Math.ceil( ( calendarDate.getTime() - oct.getTime() ) / 1000 / 60 / 60 / 24 );
				calendarDay.setAttribute( "data-value", dayNum );
				dayValues = window['dayValues' + levelCount];
				currentDayValue = dayValues[ dayNum.toString() ];
				if( typeof currentDayValue == 'object' && currentDayValue[0]['count'] == levelCount )
				{
					calendarDay.style.backgroundColor = numberToColorHex( parseInt( currentDayValue[0]['score'] )  / levelCount );
					calendarDay.title = "You got " + currentDayValue[0]['score'] + " out of " + levelCount * 10000 + "!";
				}
				else if( typeof currentDayValue == 'object' ) 
				{
					calendarDay.style.backgroundColor = '#d9bbff';
					calendarDay.title = "Continue? You're on round #" + (parseInt(currentDayValue[0]['count']) + 1) + ".";
				}
				
				calendarDay.addEventListener("click", getDaily );
            }

			calendarGrid.appendChild(calendarDay);
		}

		prevMonthButton.style.display = currentDate <= nov ? "none" : "inline";
	}
	
	function getDaily( e )
	{
		homeDay = $(e.currentTarget).data("value");
		newLevel( '/get-daily' );
	}

	// Initial render
	renderCalendar();

	// Event listeners for navigation
	prevMonthButton.addEventListener("click", () => {
		currentDate.setMonth(currentDate.getMonth() - 1);
		renderCalendar();
	});

	nextMonthButton.addEventListener("click", () => {
		currentDate.setMonth(currentDate.getMonth() + 1);
		renderCalendar();
	});
	
	function numberToColorHex( number )
	{
		const maxNum = 230;
		red = maxNum;
		green = 0;
		if( number <= 5000 )
		{
			green = Math.floor( maxNum * ( number / 5000 ) );
		}
		else
		{
			green = maxNum;
			red = Math.floor( maxNum * Math.abs( 1 - ( number - 5000 ) / 5000 ) );
		}

		const redHex = red.toString(16).padStart(2, '0');
		const greenHex = green.toString(16).padStart(2, '0');
		const blueHex = '00';

		// Combine the RGB values into a hexadecimal color code
		return `#${redHex}${greenHex}${blueHex}`;
	}
</script>