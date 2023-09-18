function newSrc( value ) {
	$('#type').val( value );
	$('button').prop('disabled', true).addClass('clicked');
	var loader = $('#loader');
	loader.show();

	$('#min-price, #max-input, #min-rent-price, #max-rent-input').val(function(_, value)
	{
		return value.replace(/,/g, '');
	});
	
	
	$('form').submit();
};

function getCities( e )
{
	var citySelect = $( e ).parent().next().children().last();
	citySelect.prop( 'disabled', true );
	$.ajax({
		url: '/get-cities',
		type: 'POST',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include the CSRF token in the request headers
		},
		data: {
			state: $( e ).children("option:selected").val()
		},
		success: function(data)
		{
			citySelect.prop( 'disabled', false ).empty().html(data);
		},
		error: function(xhr, status, error) {
			console.log('Error:', xhr.responseText);
		}
	});
}

function dropDown( e, type )
{
	if( !e.classList.contains("pressed") && $('.pressed').length > 0 )
	{
		$('svg.pressed').removeClass('pressed');
		$('.menu-wrapper').hide();
		$('.links-info').hide();
	}
	e.classList.toggle("pressed");
	$('#' + type).toggle();
	$('.menu-wrapper').toggle();
}

$(window).resize(function() {
	if ($(window).width() > 750) {
		$('.menu-wrapper svg').show();
	}
	else
	{
		$('svg.pressed').removeClass('pressed');
		$('.menu-wrapper').hide();
		$('.links-info').hide();
	}
});


 window.addEventListener('pageshow', function(event)
 {
  if( event.persisted )
  {
	location.reload();
  }
});
 $('.tab').click(function() {
	if ($(this).hasClass('tab-clicked')) {
	  return false; // Prevent the click event
	}

	$('.tab').not(this).removeClass('tab-clicked'); // Remove .clicked class from other tabs 
	$(this).addClass('tab-clicked'); // Add .clicked class to the clicked tab
	
	$('#sale-filters, #rent-filters, #submit-rent, #submit-sale').toggle();
  });
// Get all number input fields by class name
const numberInputs = document.querySelectorAll('.number-input');

// Add event listener to each number input field
numberInputs.forEach(input => {
  input.addEventListener('input', function() {
	formatNumberInput(this);
  });

  input.addEventListener('focusout', function() {

		var inputName = this.getAttribute('name');
		var value = parseInt( this.value.replace(/,/g, '').replace(/[^0-9]/g, '') );

		switch (inputName) {
			case 'minrp':
				var maxValue = parseInt( $('input[name="maxrp"]').val().replace(/,/g, '').replace(/[^0-9]/g, '') );
				if( value >= maxValue )
				{
					$('input[name="maxrp"]').val('');
				}
				break;
			case 'maxrp':
				var minValue = parseInt( $('input[name="minrp"]').val().replace(/,/g, '').replace(/[^0-9]/g, '') );
				if( minValue.length != 0 && value <= minValue)
				{
					$('input[name="minrp"]').val('');
				}
				break;
			case 'minsp':
				var maxValue = parseInt( $('input[name="maxsp"]').val().replace(/,/g, '').replace(/[^0-9]/g, '') );
				if( value >= maxValue )
				{
					$('input[name="maxsp"]').val('');
				}
				break;
			case 'maxsp':
				var minValue = parseInt( $('input[name="minsp"]').val().replace(/,/g, '').replace(/[^0-9]/g, '') );
				if( minValue.length != 0 && value <= minValue)
				{
					$('input[name="minsp"]').val('');
				}
				break;
		}
	});
});

function formatNumberInput(input) {
  // Remove existing commas and non-numeric characters
  let value = input.value.replace(/,/g, '').replace(/[^0-9]/g, '');
  
  // Format the value with commas
  value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
  
  // Set the formatted value back to the input
  input.value = value;
}


window.onbeforeunload = function() {
	if( gameStarted )
	{
		return 'There is unsaved data.';
	}
}

var csrfToken = $('meta[name="csrf-token"]').attr('content');
var gameCount = 1;
var gameStarted = false;
function newLevel()
{
	gameStarted = true;
	$("button").prop("disabled", true);
	$("#overlay").show();
	if( gameCount > 5 )
	{
		gameStarted = false;
		$.ajax({
			url: '/end-screen',
			type: 'POST',
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
			},
			success: function(data) {
				$('.screen-wrap').html(data.html);
				$("button").prop("disabled", false);
				$("#overlay").hide();
			},
			error: function(xhr, status, error) {
				console.log('Error:', xhr.responseText);
			}
		});
	}
	else
	{
		$.ajax({
			url: '/new-home',
			type: 'POST',
			dataType: 'json',
			headers: {
				'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
			},
			data: {
				count: gameCount
			},
			success: function(data) {
				$('.screen-wrap').html(data.html);
				$("button").prop("disabled", false);
				$("#overlay").hide();
			},
			error: function(xhr, status, error) {
				console.log('Error:', xhr.responseText);
			}
		});
	}
}

function checkResults( $timeout = false )
{
	$guessValue = $('#propertyPrice').val().replace(/,/g, '');
	clearInterval(timerInterval);
	if( $guessValue <= 0 && !$timeout )
	{
		alert( 'Please input a value greater then 0. ' );
		return null;
	}
	else if( $guessValue >= 10000000 && !$timeout )
	{
		$('#propertyPrice').val( '10,000,000' );
		alert( 'Please input a value less than 10,000,000. ' );
		return null;
	}

	$("button").prop("disabled", true);
	$("#overlay").show();
	$.ajax({
		url: '/check-result',
		type: 'POST',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
		},
		data: {
			propertyPrice: Number( $guessValue ),
			id: $('#propertyId').val(),
			count: gameCount
		},
		success: function(data) {
			gameCount++;
			window.scrollTo({ top: 0, behavior: 'smooth' });
			$('.screen-wrap').html(data.html);
			$("button").prop("disabled", false);
			$("#overlay").hide();
		},
		error: function(xhr, status, error) {
			console.log('Error:', xhr.responseText);
		}
	});
}

function changeName()
{
	$name = $.trim( $('#game-name').val() );
	if( $name.length == 0 )
	{
		alert( 'Please input a name' );
		return null;
	}
	else if( $name.length > 12 )
	{
		alert( 'Please input a name 12 or less characters' );
		return null;
	}

	$("button").prop("disabled", true);
	$("#overlay").show();
	$.ajax({
		url: '/change-name',
		type: 'POST',
		dataType: 'json',
		headers: {
			'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
		},
		data: {
			name: $name
		},
		success: function(data) {
			if( data == 'true')
			{
				alert('Your name has been added!!');
			}
			else
			{
				alert('Your name was not added');
			}

			$("button").prop("disabled", false);
			$("#overlay").hide();
		},
		error: function(xhr, status, error) {
			console.log('Error:', xhr.responseText);
		}
	});
}

function goToPage( e, $type )
{
	document.location.href="/results?page=" + e.value + "&type=" + $type;
}

$('#game-name').keydown( function(e){
    if ($(this).val().length > 12) { 
        $(this).val($(this).val().substr(0, 12));
    }
});
    
$('#game-name').keyup( function(e){
    if ($(this).val().length > 12) { 
        $(this).val($(this).val().substr(0, 12));
    }
});
