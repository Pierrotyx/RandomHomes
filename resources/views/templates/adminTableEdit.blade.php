<?php
$data = json_decode( json_encode ( $data ?? '' ), true );
?>
<a href="{{Request::getPathInfo()}}" class="btn btn-primary btn-round" style="background-color:#e13f5e">Go Back</a>
<form action="{{Request::fullUrl()}}" method="post">
	@csrf
	<div class="row">
		@foreach( $body as $val => $type )
			<div class="col-md-{{$type[2] ?? 6}}">
				<div class="form-group">
					<label class="bmd-label-floating">{{$type[0]}}</label>
					@if( $type[1] == 'checkbox' )
						<input
							name="{{$val}}"
							type="checkbox"
							class="form-control"
							value="{{ !empty( $data[$val] ) ? 0 : 1 }}"
							{{ ( !empty( $data[$val] ) ) ? 'checked' : '' }}
							hidden
						/>
						<input
							name="{{$val}}"
							type="checkbox"
							class="form-control"
							value="1"
							{{ ( !empty( $data[$val] ) ) ? 'checked' : '' }}
						/>
					@elseif( $type[1] == 'textArea' )
						<textarea name="{{$val}}" class="form-control" rows="5" Required>{{$data[$val] ?? ''}}</textarea>
					@else
						@php ( $money = $type[1] == 'money' )
						@php ( $type[1] = ( $money ? 'number' : $type[1] ) )
						{{$money ? '$' : ''}}
						<input
							name="{{$val}}"
							type="{{$type[1]}}"
							class="form-control"
							value="{{ ( $type[1] == 'checkbox' ) ? 1 : $data[$val] ?? ''}}"
							Required
						/>
					@endif
				</div>
			</div>
		@endforeach
		<div class="col-md-12"></div>
		<button type="submit" class="btn btn-primary pull-right col-md-2" style="background-color:#1fba2d">
			{{app('request')->input('id') == 'new' ? 'Create' : 'Edit' }} {{$name}}
		</button>
		<div class="clearfix"></div>
	</div>
</form>
