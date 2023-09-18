<div class="info-box compress">
	<div class="filter-wrapper" style="height:inherit;">
		<h2 class="title">All Viewed Homes</h2>
		<div class="tabs-container">
			<?php
			$forRent = ( Session::get('type') ?? '' ) == 'ForRent';
			?>
		  <div class="tab buying-tab {{ !( request()->type == 'ForRent' ) ? 'tab-clicked' : '' }}">For Sale</div>
		  <div class="tab renting-tab {{ request()->type == 'ForRent' ? 'tab-clicked' : '' }}">For Rent</div>
		</div>
		<hr style="margin: 0" />
		<br>
		<div id="sale-filters">
			<div class="pagination">
				Page: 
				<select onchange="goToPage(this, 'ForSale')" style="width:30%;">
					@for ($i = 1; $i <= ceil( $homeSales['count'] ) / 50 + 1; $i++)
						<option
							value="{{$i}}"
							{{ ( request()->page ?? 1 ) == $i ? 'selected' : ''; }}
						>{{ $i }}</option>
					@endfor
				</select>
			</div>
			<div class="list-grid">
				<?php
				foreach( $homeSales['data'] as $home )
				{
					$propName = $home->type == 'ForRent' ? 'Rental' : 'Sale';
					$address = explode( ',', $home->address );
					$city = $address[count($address) - 2] . ', ' . explode( ' ', $address[count($address) - 1] )[1];
					?>
					<div class="list-element">
						<a href="/results?home={{$home->id}}" class="result-link grid-link">
							<img class="list-img" src="{{$home->imgSrc}}" alt="No Preview Image" />
							{{$city}} |
							{{!empty( $home->price ) ? ( '$' . number_format( $home->price ) ) : '--'}} |
							{{!empty( $home->propertyType ) ? $home->propertyType : '--'}}
						</a>
					</div>
				<?php
				}
				?>
			</div>
			<div class="pagination">
				Page: 
				<select onchange="goToPage(this, 'ForSale')" style="width:30%;">
					@for ($i = 1; $i <= ceil( $homeSales['count'] ) / 50 + 1; $i++)
						<option
							value="{{$i}}"
							{{ ( request()->page ?? 1 ) == $i ? 'selected' : ''; }}
						>{{ $i }}</option>
					@endfor
				</select>
			</div>
		</div>
		<div id="rent-filters">
			<div class="pagination">
				Page: 
				<select onchange="goToPage(this, 'ForRent')" style="width:30%;">
					@for ($i = 1; $i <= ceil( $homeRents['count'] ) / 50 + 1; $i++)
						<option
							value="{{$i}}"
							{{ ( request()->page ?? 1 ) == $i ? 'selected' : ''; }}
						>{{ $i }}</option>
					@endfor
				</select>
			</div>
			<div class="list-grid">
				<?php
				foreach( $homeRents['data'] as $home )
				{
					$propName = $home->type == 'ForRent' ? 'Rental' : 'Sale';
					$address = explode( ',', $home->address );
					$city = $address[count($address) - 2] . ', ' . explode( ' ', $address[count($address) - 1] )[1];
					?>
					<div class="list-element">
						<a href="/results?home={{$home->id}}" class="result-link grid-link">
							<img class="list-img" src="{{$home->imgSrc}}" alt="No Preview Image" />
							{{$city}} |
							<?php
							if( $home->unitCount )
							{
								$s = $home->unitCount > 1 ? 's' : '';
								echo $home->unitCount . ' unit' . $s . ' | ';
								echo $home->rentPrice;
							}
							else
							{
								echo ( !empty( $home->price ) ? ( '$' . number_format( $home->price ) ) : '--' ) . ' | ';
								echo !empty( $home->propertyType ) ? $home->propertyType : '--';
							}
							?>
						</a>
					</div>
				<?php
				}
				?>
			</div>
			<div class="pagination">
				Page: 
				<select onchange="goToPage(this, 'ForRent')" style="width:30%;">
					@for ($i = 1; $i <= ceil( $homeRents['count'] ) / 50 + 1; $i++)
						<option
							value="{{$i}}"
							{{ ( request()->page ?? 1 ) == $i ? 'selected' : ''; }}
						>{{ $i }}</option>
					@endfor
				</select>
			</div>
		</div>
		<br>
	</div>
</div>
