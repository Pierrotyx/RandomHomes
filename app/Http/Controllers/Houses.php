<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Python;
use App\Http\Controllers\Filters;
use App\Models\Parameter;
use Carbon\Carbon;

class Houses extends Controller
{
    public function index(Request $request)
    {
        if( $request->isMethod( 'post' ) )
        {
            $homeId = $this->newSrc( $request->all() );
            $this->setSessions( $request->all() );

            return !$homeId ? Redirect::to( '/?fail=1' ) : Redirect::to( '/results/' . $homeId );
        }
        else
        {
            $states = $this->getStates();
            $parameters = [
                'head' => 'templates.heads.main',
                'body' => 'apps.random.filters',
                'parameters' => [ 'states' => $states ],
                'startIcon' => 'home',
                'description' => 'descriptions.main',
                'states' => $states,
                'citiesRent' => !empty( Session::get( 'state-rent' ) ) ? $this->getCities( Session::get( 'state-rent' ), Session::get( 'city-rent' ) ?? null ) : null,
                'citiesSale' => !empty( Session::get( 'state-sale' ) ) ? $this->getCities( Session::get( 'state-sale' ), Session::get( 'city-sale' ) ?? null ) : null
            ];
            return view( 'randomHouse', $parameters );
        }
    }

    public function newSrc( $request, $temp = false )
    {
        $output = json_decode( Python::callScript( 'test', $this->getParameters( $request ) ) );
        if( !empty( $output ) )
        {
            return $this->insertHomes( $output, $request, $temp );
        }

        return null;
    }

    public function result( Request $request )
    {
        $path = explode( '/', $request->path() );
        if( !empty( $request->get('home') ) )
        {
            return Redirect::to( '/results/' . $request->get('home') );
        }
        elseif( is_numeric( end( $path ) ) )
        {
            $results = $this->getHome( end( $path ) );
            if( !empty( $results->lotId ) )
            {
                $units = $this->getUnits( end( $path ) );
            }

            if( !empty( $results->url ) )
            {
                $address = explode( ', ', $results->address );
                $state = DB::table( 'states' )->where( 'stateCode', '=', substr( trim( end( $address ) ), 0, 2 ) )->first()->state;
                return view(
                        'randomHouse',
                        [
                            'head' => 'templates.heads.home',
                            'body' => 'apps.random.results',
                            'description' => 'descriptions.home',
                            'parameters' => ['results' => $results],
                            'startIcon' => 'home',
                            'results' => $results,
                            'filters' => $this->getFilters( end( $path ) ),
                            'id' => end( $path ),
                            'state' => $state,
                            'units' => $units ?? false
                        ]
                );
            }
        }

        return view(
                'randomHouse',
                [
                    'head' => 'templates.heads.allHomes',
                    'body' => 'apps.random.allHomes',
                    'description' => 'descriptions.allHomes',
                    'startIcon' => 'home',
                    'homeSales' => $this->getAllHomes('ForSale', $request->get('page') ?? 1 ),
                    'homeRents' => $this->getAllHomes('ForRent', $request->get('page') ?? 1 ),
                    'filters' => $this->getFilters( $request->get('home') ),
                    'id' => $request->get('home'),
                    'units' => $units ?? false
                ]
        );
        return Redirect::to( '/' );
    }
    
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
    
    public function calculatorState( Request $request )
    {
        $states = DB::table( 'states' )->get();
        $parameters = [ 'states' => $states, ];
        if( $request->path() != 'calculator' )
        {
            $path = explode( '/', $request->path() );
            $state = DB::table( 'states' )->where( 'state', '=', str_replace( '-', ' ', ucwords( end( $path ) ) ) )->first();
            if( empty( $state ) )
            {
                return Redirect::to( '/calculator' );
            }

            $parameters['current'] = $state;
        }

        $homeId = $request->get( 'home' );
        if( !empty( $homeId ) )
        {
            $home = DB::table( 'homes' )->where( 'id', '=', $homeId )->where( 'type', '=', 'ForSale' )->first();
            if( !empty( $home ) )
            {
                $parameters['home'] = $home;
            }
            else
            {
                return Redirect::to( '/calculator' . ( !empty( $parameters['current'] ) ? ('/' . strtolower( $parameters['current']->state ) ) : '' ) );
            }
        }

        return view(
			'randomHouse',
			[
				'head' => 'templates.heads.calculator',
				'body' => 'apps.calculator.main',
                'description' => 'descriptions.calculator',
				'startIcon' => 'tool',
				'parameters' => $parameters
			]
		);
    }

    public function reroll( Request $request )
    {
        $filters = Filters::getTranslatedFilters( $request->type );
        $homeId = $this->newSrc( $filters );
        //$this->setSessions( $filters );
        $page = !empty( $request->page ) ? '?page=' . $request->page : '';
        return !$homeId ? Redirect::to( '/?fail=1' ) : Redirect::to( '/results/' . $homeId . $page );
    }

    private function getAllHomes( $type, $page )
    {
        $query = DB::table('homes')
            ->whereIn('id', function ($query) use( $type ) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('homes')
                    ->groupBy('address')
                    ->where('clicked', '=', true)
                    ->where('type', '=', $type);
            })
        ;
        
        $count = $query->select(DB::raw('count(*) as total'))->first()->total;

        if( $count <= ( $page - 1 ) * 50 )
        {
            $page = floor( $count / 50 ) + 1;
        }

        $homes = $query
            ->select(
                'homes.*',
                DB::raw('(SELECT COUNT(*) FROM subHomes WHERE subHomes.homeId = homes.id) as unitCount'),
                DB::raw('(SELECT MIN(price) FROM subHomes WHERE subHomes.homeId = homes.id) as rentPrice')
            )
            ->offset(( $page - 1 ) * 50)
            ->limit(50)
            ->latest('timestamp')
        ;
        return ['data' => $homes->get(), 'count' => $count];
    }

    public function clicked( Request $request )
    {
        DB::table('homes')
            ->where( 'id', $request->id )
            ->update( ['clicked' => true] )
        ;
    }
    

    public function newGame( Request $request )
    {
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

        do
        {
            $randomId = '';
            for( $i = 0; $i < 16; $i++ )
            {
                $randomId .= chr( mt_rand( 97, 122 ) );
            }
            
            $exists = DB::table('gameTemp')->where('generateId', '=', $randomId )->exists();
        } while( $exists );
        
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
        $html = view('apps.placethatprice.newHome', ['results' => $results, 'id' => $randomId, 'round' => $request->count ] )->render();
        return response()->json( ['html' => $html] );
        
    }

    public function checkResult( Request $request )
    {
        Session::put( 'round', $request->count );
        if( $request->count == 1 )
        {
            Session::put( 'score', 0 );
            Session::put( 'gameInfo', [] );
            
            do
            {
                $randomId = '';
                for( $i = 0; $i < 16; $i++ )
                {
                    $randomId .= chr( mt_rand( 97, 122 ) );
                }
                
                $exists = DB::table('leaderboard')->where('userId', '=', $randomId )->exists();
            } while( $exists );
            
            Session::put( 'userId', $randomId );
        }

        $propertyId = DB::table('gameTemp')->where('generateId', '=', $request->id )->first()->homeId;
        $property = DB::table('homes')->where( 'id', '=', $propertyId )->first();
        DB::table('gameTemp')->where( 'generateId', '=', $request->id )->delete();
        //Max 50,000 points
        // 10,000 points per round

        if( empty( $request->propertyPrice ) )
        {
            $points = 0;
        }
        else
        {
            $divisor = max( $property->price / 500000, 1 );
            $diff = abs( $property->price - $request->propertyPrice ) / $divisor;
            $points = ceil( 10000 * exp( -0.00000445 * $diff ) ); // \ 10000 * e^{-0.000005 * $diff}
            $points = max(0, min(10000, $points ) );
        }
        
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
    
    public function finalScore( Request $request )
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

        $html = view( 'apps.placethatprice.finalScore', [ 'ranks' => $position, 'level' => $request->level ] )->render();
        return response()->json( ['html' => $html] );
    }
    
    private function getStates()
    {
        return DB::table( 'states' )
            ->select('state', 'stateCode')
            ->get()
        ;
    }
    
    private function getCities( $state, $selected = null )
    {
        if( empty( $state ) )
        {
            return false;
        }

        $cities = DB::table( 'population' )
            ->select('city')
            ->where( 'State', '=', $state )
            ->where( 'Population', '>', 1000 )
            ->orderBy( 'city', 'ASC' )
            ->distinct()
            ->get()
        ;
        
        $cityHtml = '<option value="">Select a City...</option>';
        foreach( $cities as $city )
        {
            $selectValue = $selected == $city->city ? 'selected' : '';
            $cityHtml .= '<option value="' . $city->city . '" ' . $selectValue . '>' . $city->city . '</option>';
        }
        
        return $cityHtml;
    }
    
    public function citiesOptions( Request $request )
    {
        return json_encode( $this->getCities( $request->state ) );
    }
    
    private function getParameters( $request )
    {
        if( $request['type'] == 'ForRent' )
        {
            $parametersList = [
                'RentMinPrice' => 'minrp',
                'RentMaxPrice' => 'maxrp',
                'HomeType' => 'home_type_rent',
                'BathsMinRent' => 'bathsMinRent',
                'BathsMaxRent' => 'bathsMaxRent',
                'BedsMinRent' => 'bedsMinRent',
                'BedsMaxRent' => 'bedsMaxRent',
                'LivingMinRent' => 'livingMinRent',
                'LivingMaxRent' => 'livingMaxRent',
                'LotMinRent' => 'lotMinRent',
                'LotMaxRent' => 'lotMaxRent'
            ];

            $state = $request['state-rent'] ?? '';
            $city = $request['city-rent'] ?? '';
        }
        elseif( $request['type'] == 'ForSale' )
        {
            $parametersList = [
                'MinPrice' => 'minsp',
                'MaxPrice' => 'maxsp',
                'HomeType' => 'home_type',
                'BathsMinSale' => 'bathsMinSale',
                'BathsMaxSale' => 'bathsMaxSale',
                'BedsMinSale' => 'bedsMinSale',
                'BedsMaxSale' => 'bedsMaxSale',
                'LivingMinSale' => 'livingMinSale',
                'LivingMaxSale' => 'livingMaxSale',
                'LotMinSale' => 'lotMinSale',
                'LotMaxSale' => 'lotMaxSale'
            ];

            $state = $request['state-sale'] ?? '';
            $city = $request['city-sale'] ?? '';
        }
        else
        {
            return;
        }

        $parameters = new Parameter( $request['type'], $this->getState( $state ), $city );

        foreach( $parametersList as $key => $parameter )
        {
            $getMethod = 'put' . ucfirst( $key );
            $parameters->{$getMethod}( $request[ $parameter ] ?? null );
        }

        return $parameters->run();
    }

    private function getHome( $homeId, $table = 'homes' )
    {
        $home = DB::table( $table )
            ->where( 'id', '=', $homeId )
            ->first()
        ;
        return $home;
    }

    private function getUnits( $homeId )
    {
        $home = DB::table( 'subHomes' )
            ->where( 'homeId', '=', $homeId )
            ->orderBy('id', 'ASC')
            ->get()
        ;
        return $home;
    }

    private function getFilters( $homeId )
    {
        $filters = ( array )DB::table( 'homeFilters' )
            ->where( 'homeId', '=', $homeId )
            ->first()
        ;
        if( empty( $filters ) )
        {
            return false;
        }

        return $filters;
    }

    private function getState( $stateCode )
    {
        $state = DB::table( 'states' )
            ->inRandomOrder()
        ;
        if( !empty( $stateCode ) )
        {
            $state->where( 'stateCode', '=', $stateCode );
        }

        return $state->first();
    }
    
    private function insertHomes( $data, $request, $temp = false )
    {
        $living = !empty( $data->data->livingArea ) ? $data->data->livingArea . ' Sqft' : '--';
        $homes = $temp ? 'tempHomes' : 'homes';

        $homeId = DB::table( $homes )->insertGetId(
            [
                'url' => $data->url,
                'imgSrc' => $data->data->imgSrc,
                'type' => $request['type'],
                'propertyType' => $data->data->propertyType ?? null,
                'price' => $data->data->price ?? null,
                'bedrooms' => $data->data->bedrooms ?? null,
                'bathrooms' => $data->data->bathrooms ?? null,
                'address' => $data->data->address ?? null,
                'livingSize' => $living,
                'lotAreaSize' => ( $data->data->lotAreaValue ?? '--' ) . ' ' . ( $data->data->lotAreaUnit ?? '' ),
                'lotId' => $data->data->lotId ?? null
            ]
        );

        if( $temp )
        {
            return $homeId;
        }

        if( $request['type'] == 'ForSale' )
        {
            $filters = [
                'homeId' => $homeId,
                'type' => $request['type'],
                'minPrice' => $request['minsp'] ?? null,
                'MaxPrice' => $request['maxsp'] ?? null,
                'state' => $request['state-sale'] ?? null,
                'city' => $request['city-sale'] ?? null,
                'isHouse' => in_array( 'Houses', $request['home_type'] ),
                'isCondo' => in_array( 'Condos', $request['home_type'] ),
                'isMulti' => in_array( 'Multi-family', $request['home_type'] ),
                'isManufactured' => in_array( 'Manufactured', $request['home_type'] ),
                'isLand' => in_array( 'LotsLand', $request['home_type'] ),
                'isTownHome' => in_array( 'Townhomes', $request['home_type'] ),
                'minBed' => $request['bedsMinSale'] ?? null,
                'maxBed' => $request['bedsMaxSale'] ?? null,
                'minBath' => $request['bathsMinSale'] ?? null,
                'maxBath' => $request['bathsMaxSale'] ?? null,
                'minLiving' => $request['livingMinSale'] ?? null,
                'maxLiving' => $request['livingMaxSale'] ?? null,
                'minLot' => $request['lotMinSale'] ?? null,
                'maxLot' => $request['lotMaxSale'] ?? null
            ];
        }
        else
        {
            $filters = [
                'homeId' => $homeId,
                'type' => $request['type'],
                'minPrice' => $request['minrp'] ?? null,
                'MaxPrice' => $request['maxrp'] ?? null,
                'state' => $request['state-rent'] ?? null,
                'city' => $request['city-rent'] ?? null,
                'isHouse' => in_array( 'Houses', $request['home_type_rent'] ),
                'isCondo' => in_array( 'Apartments_Condos_Co-ops', $request['home_type_rent'] ),
                'isTownHome' => in_array( 'Townhomes', $request['home_type_rent'] ),
                'minBed' => $request['bedsMinRent'] ?? null,
                'maxBed' => $request['bedsMaxRent'] ?? null,
                'minBath' => $request['bathsMinRent'] ?? null,
                'maxBath' => $request['bathsMaxRent'] ?? null,
                'minLiving' => $request['livingMinRent'] ?? null,
                'maxLiving' => $request['livingMaxRent'] ?? null,
                'minLot' => $request['lotMinRent'] ?? null,
                'maxLot' => $request['lotMaxRent'] ?? null
            ];
        }

        DB::table('homeFilters')->insert( $filters );
        if( !empty( $data->data->units ) )
        {
            foreach( $data->data->units as $unit )
            {
                DB::table('subHomes')->insert(
                    [
                        'homeId' => $homeId,
                        'beds' => $unit->beds,
                        'price' => $unit->price
                    ]
                );
            }
        }

        //Session::forget( 'homes' );
        $homes = Session::get( 'homes' ) ?? [];
        $data->data->type = $request['type'];

        foreach( $homes as $key => $home )
        {
            if( $home->address == $data->data->address )
            {
                unset( $homes[$key] );
            }
        }
        $homes[ $homeId ] =  $data->data;

        if( count( $homes ) > 10 )
        {
            unset($homes[array_key_first($homes)]);
        }

        Session::put( 'homes', $homes );
        return $homeId;
    }
    
    private function setSessions( $request )
    {
        foreach( $request as $key => $value )
        {
            Session::put( $key, $value );
        }
    }
}
