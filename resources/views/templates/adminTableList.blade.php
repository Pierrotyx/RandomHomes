<?php
$data = json_decode( json_encode ( $data ), true );
?>
<a href="{{Request::getPathInfo()}}?id=new" class="btn btn-primary btn-round" style="background-color:#5ebf2e">Add {{$name}} +</a>
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			@foreach( $head as $col )
				<th>
					{{ $col }}
				</th>
			@endforeach
		</thead>
		<tbody>
			@foreach( $data as $value )
				<tr>
					@foreach( $body as $val => $type )
						@php ( $swithCheck = is_array( $type ) ? 'bool' : $type )
						<td>
							@php ( $rowVal = $value[$val] )
							@if( !empty( $link ) )
							<a href="{{Request::getPathInfo()}}?id={{$value[$link]}}">
							@endif
							@switch( $swithCheck )
								@case('num')
									{{number_format( $rowVal )}}
								@break
								
								@case('money')
									${{number_format( $rowVal, 4, '.', ',' )}}
								@break
								
								@case('bool')
									{{$type[$rowVal]}}
								@break
								
								@default
									{{$rowVal}}
								@break
							@endswitch
							@if( !empty( $link ) )
							</a>
							@endif
						</td>
					@endforeach
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
	