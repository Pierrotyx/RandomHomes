var getTime = '';
var getCompany = 0;
anychart.onDocumentReady( getStock( 'getDay', 1 ) );
function getStock( time, company )
{
	getTime = time;
	getCompany = company;
	
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
	});
	
	$.ajax({
	  type: "POST",
	  data: {
		companyId: getCompany,
	  },
	  url:'/' + getTime,
	   success:function(getData) {
		   createChart( getData.data );
	   }
	});
}

function changeTime( e )
{
	if( e.classList.contains("active") )
	{
		return;
	}
	
	actives = document.getElementsByClassName("active")
	if( actives.length == 2 )
		document.getElementsByClassName("active")[0].classList.remove("active");
	
	e.classList.add('active');
	getStock( e.id, getCompany );
}

function changeCompany( e )
{
	getStock( getTime, e.value );
}
	
function createChart( stocks )
{
  var data = stocks.data;
  var interval = stocks.interval;
  var title = stocks.title;
  var displayType = stocks.displayType;
  var companyName = stocks.companyName;
  var dateSub = stocks.dateSub;
  var min = stocks.limits[0];
  var start = stocks.limits[1];
  var max = stocks.limits[2];
	 // create a data set on our data
var dataSet = anychart.data.set(getData( data, start ));

// map data for the first series,
// take x from the zero column and value from the first column
var firstSeriesData = dataSet.mapAs({ x: 0, value: 1, dateTime: 2, tipToolDate: 3, perc: 4 });
var middleData = dataSet.mapAs({ x: 0, value: 5 });

// create a line chart
var chart = anychart.line();

// turn on the line chart animation
chart.animation(true);
chart.xAxis().labels().format("{%dateTime}").fontSize('10px');
chart.xAxis().labels().format(function() {
	var value = this.value;
	value = eval(dateSub);
	return value
});
chart.xAxis().title(displayType);

var xScale = chart.xScale();
xScale.ticks().interval(interval);

chart.yAxis().title('Gold Value');

chart.title(title);

// set the y axis title

// turn on the crosshair
chart.crosshair().enabled(true).yLabel(false).xLabel(false).yStroke(null);

chart.background().fill({
	keys: ["#000000"],
	});

	// create the first series with the mapped data
var middle = chart.line(middleData);
	middle
	.name("")
	.stroke('3 #444444')
	.tooltip().enabled(false)
	.format("");
	middle.stroke({
		color: '#C8C8C8',
		dash: '6 8',
		opacity: .3
		});
	
	
	var firstSeries = chart.line(firstSeriesData);
	firstSeries
	.name('Your Stocks')
	.stroke('3 #9f00ff')
	.tooltip()
	.format( companyName + " : {%value}{type:number, numDecimals:3} Gold ({%perc}{type:number, numDecimals:1}\%)");
	
	chart.tooltip().titleFormat("{%tipToolDate}");
	chart.tooltip().displayMode("separated");
	
	var yScale = chart.yScale();
	yScale.minimum( min );
	yScale.maximum( max );
	
	document.getElementById('container').innerHTML = "";
	// set the container id for the line chart
	chart.container('container');
	chart.draw();
}

function getData( obj, start ) {
	var data = []
	Object.values( obj ).forEach( function( val )
	{
		data.push( [
			val.stockDisplay,
			val.stockAmount,
			val.dateTime,
			val.tipToolDate,
			val.stockAmount / start * 100 - 100,
			start
		] );
	});
	return data;
}

function getPage( page, e )
{	
	if( e.className == 'active' )
	{
		return;
	}
	
	if( e.parentNode.id == 'menu' )
	{
		document.getElementsByClassName("active")[1].className = '';
		e.className = 'active';
	}
	
	if( page == 'explore' )
	{
		$( '#exploreMenu' ).show(); 
		time = document.getElementById( getTime );
		time.className = '';
		changeTime( time );
		return;
	}
	$( '#exploreMenu' ).hide();
	getPageAjax( page );
}

function getPageAjax( page )
{
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
	});
	
	$.ajax({
	  type: "POST",
	  url:'/' + page,
	   success:function(getData) {
		   $( '#container' ).html( getData );
	   }
	});
}

function login()
{
	$( '#loginError' ).html( '' );
	username = $( 'input[name="uname"]' ).val();
	password = $( 'input[name="pwd"]' ).val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
	});
	
	$.ajax({
	  type: "POST",
	  url:'/',
	  data: {
		username: username,
		password: password,
	  },
	   success:function(getData) {
		   if( getData )
		   {
				$( '#container' ).html( getData );
		   }
		   else
		   {
			   $( 'input[name="pwd"]' ).val('');
			   $( '#loginError' ).html( 'The username or login was not correct! Please try again.' );
		   }
	   }
	});
}

function logout()
{
	if( confirm( 'Are you sure you want to logout?' ) )
	{
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		});
		
		$.ajax({
		  type: "POST",
		  url:'/',
		   success:function( getData )
		   {
			   $( '#container' ).html( getData );
		   }
		});
	}
}

function register()
{
	$( '#loginError' ).html( '' );
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
	});

	$.ajax({
	  type: "POST",
	  url:'/postRegister',
	  data: {
		Username: $( 'input[name="uname"]' ).val(),
		Email: $( 'input[name="email"]' ).val(),
		Birthday: $( 'input[name="bday"]' ).val(),
		Password: $( 'input[name="pwd"]' ).val(),
		Check: $( 'input[name="check"]' ).val()
	  },
	   success:function(getData) {
		   if( getData == '' )
		   {
				getPageAjax( 'getProfile' );
				alert("You have succefully made a an account!");
		   }
		   else
		   {
			   $( 'input[name="pwd"]' ).val('');
			   $( 'input[name="check"]' ).val('');
			   $( '#loginError' ).html( getData );
			   $('#pwd').attr('type', 'password')
			   $('#check').attr('type', 'password')
		   }
	   }
	});
}

function togglePassword( type )
{
	$input = $( '#' + type );
	$input.attr( 'type', ( $input.attr('type') == 'password' ? 'text' : 'password' ) );
}

