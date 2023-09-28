<?php
$i = 1;
if( count( $boardInfo ) != 0 )
{
	foreach( $boardInfo as $info )
	{
		$turn = ( $turn ?? '' ) == 'even' ? 'odd' : 'even';
		?>
		<div class="leaderboard-row {{$turn}}">
			<div class="rank">{{number_format( $i )}}</div>
			<div class="player">{{$info->name}}</div>
			<div class="score">{{number_format( $info->score )}}</div>
			<div class="score">{!! $info->timestamp !!}</div>
		</div>
		<?php
		$i++;
	}
}
else
{
	?>
	<h2>No Scores. Be the first!</h2>
	<?php
}
?>