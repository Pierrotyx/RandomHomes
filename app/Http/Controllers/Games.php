<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Python;
use App\Http\Controllers\Filters;
use App\Models\Parameter;
use Carbon\Carbon;

class Games extends Controller
{   
    public function priceLeaderboard()
    {
        return view(
            'randomHouse',
            [
                'head'        => 'templates.heads.placeLeader',
                'body'        => 'apps.placethatprice.leaderboard',
                'description' => 'descriptions.placeLeader',
                'startIcon'   => 'game',
                'boardInfo'   => $this->getLeaderboard( 'place5', '1 day' )
            ]
        );
    }
    
    public function changeLeaderboard( Request $request )
    {
        $leaderboard = $this->getLeaderboard( $request->type, $request->interval );
        $html = view('templates.leaderboard', ['boardInfo' => $leaderboard ] )->render();
        return response()->json( ['html' => $html] );
    }
    
    private function getLeaderboard( $type = 'place5', $interval = null, $order = 'desc' )
    {
        $query = DB::table( 'leaderboard' )
            ->limit( 500 )
            ->orderBy( 'score', $order )
            ->where( 'type', '=', $type )
        ;

        if( !empty( $interval ) )
        {
            $query = $query->where( 'timestamp', '>=', date( 'Y-m-d H:i:s', strtotime( '-' . $interval ) ) );
        }
        
        $priceLeaderboard = $query->get();
        
        foreach( $priceLeaderboard as &$row )
        {
            $now = Carbon::now();
            $timestamp = Carbon::parse($row->timestamp);
            $interval = Carbon::now()->diff($timestamp);
            
            $hours   = $interval->h + ($interval->days * 24);
            $minutes = $interval->i;
            $days    = $interval->days;
            $months  = floor($days / 30);
            $years   = floor($days / 365);

            $seperator = '<br>';
            if( $years >= 1 )
            {
                $firstTime = $years . ' Year' . ( $years != 1 ? 's' : '' );
                $secondTime = $months % 12 . ' Month' . ( $months % 12 != 1 ? 's' : '' );
            }
            elseif( $months >= 1 )
            {
                $firstTime = $months . ' Month' . ( $months != 1 ? 's' : '' );
                $secondTime = $days % 30 . ' Day' . ( $days % 30 != 1 ? 's' : '' );
            }
            elseif( $days >= 1 )
            {
                $firstTime = $days % 30 . ' Day' . ( $days != 1 ? 's' : '' );
                $secondTime = $hours % 24 . ' Hour' . ( $hours % 24 != 1 ? 's' : '' );
            }
            elseif( $hours >= 1 )
            {
                $firstTime = $hours . ' Hour' . ( $hours != 1 ? 's' : '' );
                $secondTime = $minutes % 60 . ' Minute' . ( $minutes % 60 != 1 ? 's' : '' );
            }
            else
            {
                $firstTime = $minutes . ' Minute' . ( $minutes % 60 != 1 ? 's' : '' );
                $secondTime = '';
            }

            if( empty( $secondTime ) or substr( $secondTime, 0, 1 ) === '0' )
            {
                $secondTime = '';
                $seperator = ' ';
            }

            $secondTime = substr( $secondTime, 0, 1 ) === '0' ? '' : $secondTime;
            $row->timestamp = $firstTime . $seperator . $secondTime . ' ago';
        }
        
        return $priceLeaderboard;
    }

    public function newGame( Request $request )
    {
        if( $request->count - 1 == $request->level )
        {
            return $this->finalScore( $request );
        }

        $gameProperties = Session::get( 'gameProperties' ) ?? [];
        $results = ( array ) DB::table( 'homes' )
            ->select( 'homes.*' )
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('homes')
                    ->where( 'type', '=', 'ForSale' )
                    //->whereIn( 'propertyType', ['SINGLE_FAMILY', 'TOWNHOUSE', 'CONDO'] )
                    ->groupBy('address');
            })
            ->whereNotIn( 'id', $gameProperties )
            ->where( 'price', '<', 10000000 )
            ->where( 'price', '>', 50000 )
            ->where( 'bedrooms', '>', 0 )
            ->where( 'bathrooms', '>', 0 )
            ->inRandomOrder()
            ->first()
        ;

        $randomId = $this->generateCode( 'gameTemp', 'generateId' );
        
        DB::table('gameTemp')->insert([ 'homeId' =>  $results['id'], 'generateId' => $randomId ]);
        array_push( $gameProperties, $results['id'] );
        Session::put( 'gameProperties', $gameProperties );

        $addressArray = explode( ', ', $results['address'] );
        $results['city'] = $addressArray[1];
        $state = substr( $addressArray[2], 0, 2 );
        $results['state'] = DB::table( 'states' )
            ->select( 'state' )
            ->where( 'stateCode', '=', strtoupper( $state ) )
            ->first()->state
        ;
        $html = view(
            'apps.placethatprice.newHome',
            [
                'results' => $results,
                'id' => $randomId,
                'round' => $request->count,
                'url' => '/check-result'
            ]
        )->render();
        return response()->json( ['html' => $html] );
        
    }

    public function checkResult( Request $request )
    {
        Session::put( 'round', $request->count );
        if( $request->count == 1 )
        {
            Session::put( 'score', 0 );
            Session::put( 'gameInfo', [] );
            
            Session::put( 'userId', $this->generateCode( 'leaderboard', 'userId' ));
        }

        $propertyId = DB::table('gameTemp')->where('generateId', '=', $request->id )->first()->homeId;
        $property = DB::table('homes')->where( 'id', '=', $propertyId )->first();
        DB::table('gameTemp')->where( 'generateId', '=', $request->id )->delete();
        //Max 50,000 points
        // 10,000 points per round

        $points = $this->getPoints( $property->price, $request->propertyPrice );
        
        $info =
        [
            'img'     => $property->imgSrc,
            'address' => $property->address,
            'url'     => $property->url,
            'score'   => $points,
            'price'   => $property->price
        ];
        Session::put( 'score', Session::get( 'score' ) + $points );
        $currentGame = Session::get( 'gameInfo' );
        array_push( $currentGame, $info );
        Session::put( 'gameInfo', $currentGame );

        $html = view(
            'apps.placethatprice.score',
            [
                'total'    => ceil( Session::get( 'score' ) ),
                'guess'    => $request->propertyPrice,
                'info'     => $info,
                'round'    => $request->count,
                'level'    => $request->level,
                'homeUrl'  => '/new-home',
            ]
        )->render();
        return response()->json( ['html' => $html] );
    }
    
    public function changeName( Request $request )
    {
        if( empty( Session::get( 'userId' ) ) )
        {
            return json_encode('false');
        }

        DB::table( 'leaderboard' )
            ->where( 'userId', Session::get( 'userId' ) )
            ->update( [ 'name' =>  $request->name ] )
        ;
        
        return json_encode('true');
    }
    
    private function finalScore( $request )
    {
        Session::put( 'round', 0 );
        $type = 'place' . $request->level;
        DB::table('leaderboard')->insert(
            [
                'name' =>  'Anonymous',
                'score' => ceil( Session::get( 'score' ) ),
                'userId' => Session::get( 'userId' ),
                'type' => $type
            ]
        );

        $datePeriods = [
            'Day' => Carbon::now()->subDay(),
            'Week' => Carbon::now()->subWeek(),
            'Month' => Carbon::now()->subMonth(),
            'Year' => Carbon::now()->subYear(),
            'All Time' => Carbon::now()->subYears(1000)
        ];

        foreach( $datePeriods as $key => $date )
        {
            $interval = $date->format('Y-m-d H:i:s');

            $score = DB::table('leaderboard')
                ->selectRaw('COUNT(*) + 1 as position')
                ->where(
                    'score',
                    '>',
                    DB::table('leaderboard')
                        ->where( 'userId', Session::get( 'userId' ) )
                        ->value('score')
                )
                ->where( 'type', $type )
                ->where( 'timestamp', '>=', $interval )
                ->first()->position
            ;
            if( $score <= 500 )
            {
                $position[ $key ] = $score;
            }
        }

        $html = view(
            'apps.placethatprice.finalScore',
            [
                'ranks' => $position,
                'level' => $request->level,
                'total' => Session::get( 'score' )
            ]
        )->render();
        return response()->json( ['html' => $html] );
    }
    
    public function dailyStart( Request $request )
    {
        $userDailyScores = DB::table( 'dailyPlaceUsers' )
            ->select('day', DB::raw('SUM(dailyPlaceUsers.score) as scores'),  DB::raw('COUNT(dailyPlaceUsers.score) as count'))
            ->leftJoin( 'dailyPlace', 'dailyPlace.id', '=', 'dailyPlaceUsers.dailyId' )
            ->where( 'dailyPlaceUsers.userCode', '=', $request->cookie( 'dailyPlace' ) )
            ->groupBy( 'dailyPlace.day' )
        ;
        
        $place3 = (clone $userDailyScores)
            ->where( 'dailyPlace.type', 'place3' )
            ->get()
            ->mapToGroups(function ($item) { return [$item->day => ['score' => $item->scores, 'count' => $item->count]];})
        ;
       $place5 = (clone $userDailyScores)
            ->where( 'dailyPlace.type', 'place5' )
            ->get()
            ->mapToGroups(function ($item) { return [$item->day => ['score' => $item->scores, 'count' => $item->count]];})
        ;
        $place10 = (clone $userDailyScores)
            ->where( 'dailyPlace.type', 'place10' )
            ->get()
            ->mapToGroups(function ($item) { return [$item->day => ['score' => $item->scores, 'count' => $item->count]];})
        ;

        return view(
            'randomHouse',
            [
                'head' => 'templates.heads.placeDaily',
                'body' => 'apps.placethatprice.daily',
                'description' => 'descriptions.placeDaily',
                'startIcon' => 'game',
                'scores' => [
                    'place3' => $place3,
                    'place5' => $place5,
                    'place10' => $place10,
                ],
            ]
        );
    }
    
    public function getDaily( Request $request )
    {
        Session::put( 'currentDaily', $request->level );
        if( !$request->hasCookie( 'dailyPlace' ) )
        {
            $expirationMinutes = 365 * 50 * 24 * 60;
            $userCode = $this->generateCode( 'dailyPlaceUsers', 'userCode', 25 );
            $cookie =
                cookie(
                    'dailyPlace',
                    $userCode,
                    $expirationMinutes
                )
            ;
        }
        else
        {
            $userCode = $request->cookie( 'dailyPlace' );
        }

        $currentUser = $this->currentUserDaily( $userCode, $request );

        $round = $currentUser->count();
        if( $round == $request->level )
        {
            $html = view(
                'apps.placethatprice.finalScore',
                [
                    'ranks' => [],
                    'level' => $request->level,
                    'dailyValues' => $this->getDailyValues( $request, $userCode ),
                    'total' => $currentUser->sum( 'score' )
                ]
            )->render();
            return response()->json( [ 'html' => $html ] );
        }
        $currentDay = $this->getSingleDaily( $request, $round );
        $data = [
            'userCode' =>  $userCode,
            'dailyId' => $currentDay['dayId'], // Set the dailyId to the appropriate value
            'score' => 0,   // Set the score to the appropriate value
        ];

        $round++;
        DB::table( 'dailyPlaceUsers' )->insert( $data );
        $html = view(
            'apps.placethatprice.newHome',
            [
                'results' => $currentDay,
                'round' => $round,
                'url' => '/check-daily',
                'id' => $request->day,
            ]
        )->render();
        
        if( isset( $cookie ) )
        {
             return response()->json(['html' => $html])->cookie($cookie);
        }
    
        return response()->json(['html' => $html]);
    }

    public function checkDaily( Request $request )
    {
        if( !$request->hasCookie( 'dailyPlace' ) )
        {
            return false;
        }

        $request->day = $request->id;
        $round = $this->currentUserDaily( $request->cookie( 'dailyPlace' ), $request )->count();
        $property = $this->getSingleDaily( $request, $round - 1 );

        $points = $this->getPoints( $request->propertyPrice, $property['price'] );
        
        $info =
        [
            'img'     => $property['imgSrc'],
            'address' => $property['address'],
            'url'     => $property['url'],
            'score'   => $points,
            'price'   => $property['price']
        ];

        DB::table( 'dailyPlaceUsers' )
            ->where( 'userCode', $request->cookie( 'dailyPlace' ) )
            ->where( 'dailyId', $property['dayId'] )
            ->update( [ 'score' =>  $points ] )
        ;
        
        $totalScore = DB::table( 'dailyPlaceUsers' )
            ->leftJoin( 'dailyPlace', 'dailyPlace.id', '=', 'dailyPlaceUsers.dailyId' )
            ->where( 'dailyPlaceUsers.userCode', '=', $request->cookie( 'dailyPlace' ) )
            ->where( 'dailyPlace.day', '=', $request->day )
            ->where( 'dailyPlace.type', '=', 'place' . $request->level )
            ->sum('score');
        ;

        $html = view(
            'apps.placethatprice.score',
            [
                'total'    => $totalScore,
                'guess'    => $request->propertyPrice,
                'info'     => $info,
                'round'    => $round,
                'level'    => $request->level,
                'homeUrl'  => '/get-daily',
                'gameOff'  => true
            ]
        )->render();
        return response()->json( ['html' => $html] );
    }

    private function getDailyValues( $request, $userCode )
    {
        return DB::table('dailyPlace')
            ->select('homes.*', 'dailyPlaceUsers.score AS score')
            ->where('dailyPlace.day', $request->day)
            ->where('dailyPlace.type', 'place' . $request->level)
            ->where('dailyPlaceUsers.userCode', $userCode )
            ->orderBy('dailyPlaceUsers.id', 'ASC')
            ->leftJoin('homes', 'dailyPlace.home', '=', 'homes.id')
            ->leftJoin('dailyPlaceUsers', 'dailyPlace.id', '=', 'dailyPlaceUsers.dailyId')
            ->get()
        ;
    }
    
    private function currentUserDaily( $userCode, $request )
    {
        return DB::table( 'dailyPlaceUsers' )
            ->leftJoin( 'dailyPlace', 'dailyPlace.id', '=', 'dailyPlaceUsers.dailyId' )
            ->where( 'dailyPlaceUsers.userCode', '=', $userCode )
            ->where( 'dailyPlace.day', '=', $request->day )
            ->where( 'dailyPlace.type', '=', 'place' . $request->level )
            ->get()
        ;
    }
    
    private function getSingleDaily( $request, $round )
    {
        $results = (array) DB::table('dailyPlace')
            ->select('homes.*', 'dailyPlace.id AS dayId')
            ->where('dailyPlace.day', $request->day)
            ->where('dailyPlace.type', 'place' . $request->level )
            ->orderBy('dailyPlace.id', 'ASC')
            ->leftJoin('homes', 'dailyPlace.home', '=', 'homes.id')
            ->skip( $round )
            ->first()
        ;
        
        $addressArray = explode( ', ', $results['address'] );
        $results['city'] = $addressArray[1];
        $state = substr( $addressArray[2], 0, 2 );
        $results['state'] = DB::table( 'states' )
            ->select( 'state' )
            ->where( 'stateCode', '=', strtoupper( $state ) )
            ->first()->state
        ;
        
        return $results;
    }
    
    private function getPoints( $homePrice, $guess )
    {
        if( empty( $guess ) )
        {
            $points = 0;
        }
        else
        {
            $divisor = max( $homePrice / 500000, 1 );
            $diff = abs( $homePrice - $guess ) / $divisor;
            $points = ceil( 10000 * exp( -0.00000445 * $diff ) ); // \ 10000 * e^{-0.000005 * $diff}
            $points = max(0, min(10000, $points ) );
        }
        
        return $points;
    }
    
    private function setCookie( $request )
    {
      $minutes = 60;
      $response = new Response('Set Cookie');
      $response->withCookie(cookie('name', 'MyValue', $minutes));
      return $response;
   }
    
    private function getStates()
    {
        return DB::table( 'states' )
            ->select('state', 'stateCode')
            ->get()
        ;
    }
    
    private function setSessions( $request )
    {
        foreach( $request as $key => $value )
        {
            Session::put( $key, $value );
        }
    }
    
    private function generateCode( $table, $column, $length = 16 )
    {
        do
        {
            $randomId = '';
            for( $i = 0; $i < $length; $i++ )
            {
                $randomId .= chr( mt_rand( 97, 122 ) );
            }
            
            if( $table )
            {
                $exists = DB::table( $table )->where( $column, '=', $randomId )->exists();
            }

        } while( $exists );
        
        return $randomId;
    }
}
