<?php
$bedValues = [1, 2, 3, 4, 5, 6];
$bathValues = [1, 2, 3, 4];
$livingValues = [10, 100, 500, 750, 1000, 1250, 1500, 1750, 2000, 2250, 2500, 2750, 3000, 3500, 4000, 5000, 7500];
$lotValues = [
	100 => '100 Sqft',
	1000 => '1,000 Sqft',
	2000 => '2,000 Sqft',
	3000 => '3,000 Sqft',
	4000 => '4,000 Sqft',
	5000 => '5,000 Sqft',
	7500 => '7,500 Sqft',
	10890 => '1/4 acres',
	21780 => '1/2 acres',
	43560 => '1 acre',
	87120 => '2 acres',
	217800 => '5 acres',
	435600 => '10 acres',
	871200 => '20 acres',
	2178000 => '50 acres',
	4356000 => '100 acres'
];
$forRent = ( Session::get('type') ?? '' ) == 'ForRent';
?>
<div class="filter-box">
	<form method="POST" action="">
		@csrf <!-- {{ csrf_field() }} -->
		<div class="filter-wrapper">
			<div class="tabs-container">
				<?php
				$forRent = ( Session::get('type') ?? '' ) == 'ForRent';
				?>
			  <div class="tab buying-tab {{ !$forRent ? 'tab-clicked' : '' }}">For Sale</div>
			  <div class="tab renting-tab {{ $forRent ? 'tab-clicked' : '' }}">For Rent</div>
			</div>
			<hr style="margin: 0" />
			<input type="hidden" name="type" id="type" value="" />
			<div id="sale-filters">
				<div class="filter-body">
					<div class="filter-row">
						<div class="input-box flex">
							<div class="form-row">
								<label for="minbp">Min Sale Price:</label>
								<input
									type="text"
									id="min-price"
									name="minsp"
									class="number-input"
									min="0"
									max="500000000"
									value="{{!empty( Session::get('minsp') ) ? number_format( Session::get('minsp') ) : ''}}"
								/>
							</div>
							<div class="form-row">
								<label for="maxbp">Max Sale Price:</label>
								<input
									type="text"
									id="max-input"
									name="maxsp"
									class="number-input"
									min="0"
									max="500000000"
									value="{{!empty( Session::get('maxsp') ) ? number_format( Session::get('maxsp') ) : ''}}"
								/>
							</div>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="state">State:</label>
								<select class="state" name="state-sale" onchange="getCities( this )">
									<option value="">Select a State...</option>
									<?php
									foreach( $states as $state )
									{
										?>
										<option
											value="{{$state->stateCode}}"
											{{ ( !empty( Session::get('state-sale') ) and Session::get('state-sale') == $state->stateCode ) ? 'selected' : ''}}
										>{{$state->state}}</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="state">City:</label>
								<select class="state" name="city-sale" <?= empty( Session::get( 'state-sale' ) ) ? 'disabled' : ''; ?> >
									<?php 
									if( !empty( Session::get( 'state-sale' ) ) )
									{
										echo $citiesSale;
									}
									else
									{
										?>
										<option value="">Select State</option>
										<?php
										
									}
									?>
								</select>
							</div>
						</div>
						<br>
						<div class="input-box flex check">
							<input type="hidden" name="home_type[]" value="">
							<?php
							$house_types = [
								'Houses' => 'Houses',
								'Condos' => 'Condos',
								'Multi-family' => 'Multi-family',
								'Manufactured' => 'Manufactured',
								'LotsLand' => 'Lots & Lands',
								'Townhomes' => 'Townhomes',
							];
							foreach( $house_types as $key => $house_type)
							{
								?>
								<div>
									<label for="{{$key}}">{{$house_type}}</label>
									<input
										type="checkbox"
										id="{{$key}}"
										name="home_type[]"
										value="{{$key}}"
										{{ ( !empty( Session::get('home_type') ) and in_array( $key, Session::get('home_type') ) ) ? 'checked' : ''}}
									/>
								</div>
								<?php
								
							}
							?>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="bedroom-sale">Bedroom Min:</label>
								<select class="bedroom-sale" name="bedsMinSale">
									<option value="">Any</option>
									<?php
									foreach( $bedValues as $value )
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('bedsMinSale') ) ==  $value ? 'selected' : '' }}
										>{{$value}}</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="bedroom-sale">Bedroom Max:</label>
								<select class="bedroom-sale" name="bedsMaxSale">
									<option value="">Any</option>
									<?php
									foreach( $bedValues as $value)
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('bedsMaxSale') ) ==  $value ? 'selected' : '' }}
										>{{$value}}</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="bathroom-sale">Bathroom Min:</label>
								<select class="bathroom-sale" name="bathsMinSale">
									<option value="">Any</option>
									<?php
									foreach( $bathValues as $value)
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('bathsMinSale') ) ==  $value ? 'selected' : '' }}
										>{{$value}}</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="bathroom-sale">Bathroom Max:</label>
								<select class="bathroom-sale" name="bathsMaxSale">
									<option value="">Any</option>
									<?php
									foreach( $bathValues as $value)
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('bathsMaxSale') ) ==  $value ? 'selected' : '' }}
										>{{$value}}</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="bedroom-rent">Living Size Min:</label>
								<select class="bedroom-rent" name="livingMinSale">
									<option value="">Any</option>
									<?php
									foreach( $livingValues as $value )
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('livingMinSale') ) ==  $value ? 'selected' : '' }}
										>{{ number_format( (int) $value ) }} Sqft</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="bedroom-rent">Living Size Max:</label>
								<select class="bedroom-rent" name="livingMaxSale">
									<option value="">Any</option>
									<?php
									foreach( $livingValues as $value )
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('livingMaxSale') ) ==  $value ? 'selected' : '' }}
										>{{ number_format( (int) $value ) }} Sqft</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="bathroom-rent">Lot Size Min:</label>
								<select class="bathroom-rent" name="lotMinSale">
									<option value="">Any</option>
									<?php
									foreach( $lotValues as $key => $value )
									{
										?>
										<option
											value="{{$key}}"
											{{ ( Session::get('lotMinSale') ) ==  $key ? 'selected' : '' }}
										>{{ $value }}</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="bathroom-rent">Lot Size Min:</label>
								<select class="bathroom-rent" name="lotMaxSale">
									<option value="">Any</option>
									<?php
									foreach( $lotValues as $key =>$value )
									{
										?>
										<option
											value="{{$key}}"
											{{ ( Session::get('lotMaxSale') ) ==  $key ? 'selected' : '' }}
										>{{ $value }}</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="filter-row">
					</div>
				</div>
			</div>
			<div id="rent-filters">
				<div class="filter-body">
					<div class="filter-row">
						<div class="input-box flex">
							<div class="form-row">
								<label for="minrp">Min Rent Price:</label>
								<input
									type="text"
									id="min-rent-price"
									name="minrp"
									class="number-input"
									min="0"
									max="500000000"
									value="{{!empty( Session::get('minrp') ) ? number_format( Session::get('minrp') ) : ''}}"
								/>
							</div>
							<div class="form-row">
								<label for="maxrp">Max Rent Price:</label>
								<input
									type="text"
									id="max-rent-input"
									name="maxrp"
									class="number-input"
									min="0"
									max="500000000"
									value="{{!empty( Session::get('maxrp') ) ? number_format( Session::get('maxrp') ) : ''}}"
								/>
							</div>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="state">State:</label>
								<select class="state" name="state-rent" onchange="getCities( this )">
									<option value="">Select a State...</option>
									<?php
									foreach( $states as $state )
									{
										?>
										<option
											value="{{$state->stateCode}}"
											{{ ( !empty( Session::get('state-rent') ) and Session::get('state-rent') == $state->stateCode ) ? 'selected' : ''}}
										>{{$state->state}}</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="state">City:</label>
								<select class="state" name="city-rent" <?= empty( Session::get( 'state-rent' ) ) ? 'disabled' : ''; ?> >
									<?php 
									if( !empty( Session::get( 'state-rent' ) ) )
									{
										echo $citiesRent;
									}
									else
									{
										?>
										<option value="">Select State</option>
										<?php
										
									}
									?>
								</select>
							</div>
						</div>
						<br>
						<div class="input-box flex check">
							<input type="hidden" name="home_type_rent[]" value="">
							<?php
							$house_types_rent = [
								'Houses' => 'Houses',
								'Apartments_Condos_Co-ops' => 'Apartments',
								'Townhomes' => 'Townhomes',
							];
							foreach( $house_types_rent as $key => $house_type )
							{
								?>
								<div>
									<label for="{{$key}}">{{$house_type}}</label>
									<input
										type="checkbox"
										id="{{$key}}"
										name="home_type_rent[]"
										value="{{$key}}"
										{{ ( !empty( Session::get('home_type_rent') ) and in_array( $key, Session::get('home_type_rent') ) ) ? 'checked' : ''}}
									/>
								</div>
								<?php
							}
							?>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="bedroom-rent">Bedroom Min:</label>
								<select class="bedroom-rent" name="bedsMinRent">
									<option value="">Any</option>
									<?php
									foreach( $bedValues as $value )
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('bedsMinRent') ) ==  $value ? 'selected' : '' }}
										>{{$value}}</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="bedroom-rent">Bedroom Max:</label>
								<select class="bedroom-rent" name="bedsMaxRent">
									<option value="">Any</option>
									<?php
									foreach( $bedValues as $value )
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('bedsMaxRent') ) ==  $value ? 'selected' : '' }}
										>{{$value}}</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="bathroom-rent">Bathroom Min:</label>
								<select class="bathroom-rent" name="bathsMinRent">
									<option value="">Any</option>
									<?php
									foreach( $bathValues as $value)
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('bathsMinRent') ) ==  $value ? 'selected' : '' }}
										>{{$value}}</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="bathroom-rent">Bathroom Max:</label>
								<select class="bathroom-rent" name="bathsMaxRent">
									<option value="">Any</option>
									<?php
									foreach( $bathValues as $value)
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('bathsMaxRent') ) ==  $value ? 'selected' : '' }}
										>{{$value}}</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="bedroom-rent">Living Size Min:</label>
								<select class="bedroom-rent" name="livingMinRent">
									<option value="">Any</option>
									<?php
									foreach( $livingValues as $value )
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('livingMinRent') ) ==  $value ? 'selected' : '' }}
										>{{ number_format( (int) $value ) }} Sqft</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="bedroom-rent">Living Size Max:</label>
								<select class="bedroom-rent" name="livingMaxRent">
									<option value="">Any</option>
									<?php
									foreach( $livingValues as $value )
									{
										?>
										<option
											value="{{$value}}"
											{{ ( Session::get('livingMaxRent') ) ==  $value ? 'selected' : '' }}
										>{{ number_format( (int) $value ) }} Sqft</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
						<div class="input-box flex">
							<div class="form-row">
								<label for="bathroom-rent">Lot Size Min:</label>
								<select class="bathroom-rent" name="lotMinRent">
									<option value="">Any</option>
									<?php
									foreach( $lotValues as $key => $value )
									{
										?>
										<option
											value="{{$key}}"
											{{ ( Session::get('lotMinRent') ) ==  $key ? 'selected' : '' }}
										>{{ $value }}</option>
										<?php
									}
									?>
								</select>
							</div>
							<div class="form-row">
								<label for="bathroom-rent">Lot Size Min:</label>
								<select class="bathroom-rent" name="lotMaxRent">
									<option value="">Any</option>
									<?php
									foreach( $lotValues as $key => $value )
									{
										?>
										<option
											value="{{$key}}"
											{{ ( Session::get('lotMaxRent') ) ==  $key ? 'selected' : '' }}
										>{{ $value }}</option>
										<?php
									}
									?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="filter-row">
				</div>
			</div>
		</div>
		<button type="submit" value="ForSale" id="submit-sale" onclick="newSrc(this.value)" class="submit-button"><span>Find a Sale</span></button>
		<button type="submit" value="ForRent" id="submit-rent" onclick="newSrc(this.value)" class="submit-button"><span>Find a Rental</span></button>
	</form>
</div>