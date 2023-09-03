<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Python;
use App\Http\Controllers\Filters;
use App\Models\Parameter;

class Houses extends Controller
{
    public function index(Request $request)
    {
        if( $request->isMethod( 'post' ) )
        {
            $homeId = $this->newSrc( $request->all() );
            $this->setSessions( $request->all() );

            return !$homeId ? Redirect::to( '/?fail=1' ) : Redirect::to( '/results?home=' . $homeId );
        }
        else
        {
            return view( 'randomHouse', [ 'states' => $this->getStates() ] );
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
        if( !empty( $request->get('home') ) )
        {
            $results = $this->getHome( $request->get('home') );
            if( !empty( $results->lotId ) )
            {
                $units = $this->getUnits( $request->get('home') );
            }

            if( !empty( $results->url ) )
            {
                return view(
                        'randomHouse',
                        [
                            'results' => $results,
                            'filters' => $this->getFilters( $request->get('home') ),
                            'id' => $request->get('home'),
                            'units' => $units ?? false
                        ]
                );
            }
        }
        return Redirect::to( '/' );
    }

    public function reroll( Request $request )
    {
        $filters = Filters::getTranslatedFilters( $request->type );
        $homeId = $this->newSrc( $filters );
        //$this->setSessions( $filters );
        return Redirect::to( '/results?home=' . $homeId );
    }

    public function clicked( Request $request )
    {
        DB::table('homes')
            ->where( 'id', $request->id )
            ->update( ['clicked' => true] )
        ;
    }
    
    public function homePrice( Request $request )
    {
        if( $request->isMethod( 'post' ) )
        {
            $homeId = $this->newSrc( $request->all() );
            return Redirect::to( '/results?home=' . $homeId );
        }
        else
        {
            return view( 'randomHouse', [ 'states' => $this->getStates() ] );
        }
    }

    public function newGame( Request $request )
    {
        $count = DB::table('homes')->count();
        if( $count > 10000 and rand( 1, $count + 1000 ) > 1000 )
        {
            $state = DB::table( 'homes' )
                ->inRandomOrder()
            ;
        }
        else
        {
            $homeType = rand(1, 100);
            if( $homeType > 50 )
            {
                $homeType = ['Houses'];
            }
            elseif( $homeType > 25 )
            {
                $homeType = ['Condos'];
            }
            else
            {
                $homeType = ['Townhomes'];
            }
            $customRequest = [
                'type'          => 'ForSale',
                'minsp'         => rand( 20000, 750000),
                'home_type'     => $homeType,
                'bedsMinSale'   => 1,
                'maxsp'         => null,
                'bathsMinSale'  => null,
                'bathsMaxSale'  => null,
                'bedsMaxSale'   => null
            ];

            $results = json_decode( json_encode( $this->getHome( $this->newSrc( $customRequest, true ), 'tempHomes' ) ), true );
            $state = substr( explode( ', ', $results['address'] )[2], 0, 2 );
            $results['state'] = DB::table( 'states' )
                ->select( 'state' )
                ->where( 'stateCode', '=', strtoupper( $state ) )
                ->first()
            ;
            $html = view('homePriceGenerate', ['results' => $results, 'test' => $results, 'round' => $request->count ] )->render();
            return response()->json( ['html' => $html] );
        }
        
    }

    public function checkResult( Request $request )
    {
        Session::put( 'round', $request->count );
        if( $request->count == 1 )
        {
            Session::put( 'score', 0 );
        }

        if( $property = DB::table('tempHomes')->where( 'id', '=', $request->id )->first() )
        {
            //Max 50,000 points
            // 10,000 points per round
            $divisor = max( $property->price / 500000, 1 );
            $diff = abs( $property->price - $request->propertyPrice ) / $divisor;
            $points = ceil( 10000 * exp( -0.00000445 * $diff ) ); // \ 10000 * e^{-0.000005 * $diff}
            $points = max(0, min(10000, $points ) );
            
            Session::put( 'score', Session::get( 'score' ) + $points );
            DB::table('tempHomes')->where( 'id', '=', $request->id )->delete();

            unset( $property->id );
            DB::table('homes')->insert( (array) $property );

            $html = view(
                'homePriceScore',
                [
                    'points'   => round( $points ),
                    'total'    => ceil( Session::get( 'score' ) ),
                    'guess'    => $request->propertyPrice,
                    'property' => $property,
                    'round'    => $request->count,
                ]
            )->render();
            return response()->json( ['html' => $html] );
        }
        else
        {
            return $request->id . ' test';
        }
        
    }
    
    private function getStates()
    {
        return DB::table( 'states' )
            ->select('state', 'stateCode')
            ->get()
        ;
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
                'BedsMaxRent' => 'bedsMaxRent'
            ];

            $state = $request['state-rent'] ?? '';
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
                'BedsMaxSale' => 'bedsMaxSale'
            ];

            $state = $request['state-sale'] ?? '';
        }
        else
        {
            return;
        }

        $parameters = new Parameter( $request['type'], $this->getState( $state ) );

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
                'isHouse' => in_array( 'Houses', $request['home_type'] ),
                'isCondo' => in_array( 'Condos', $request['home_type'] ),
                'isMulti' => in_array( 'Multi-family', $request['home_type'] ),
                'isManufactured' => in_array( 'Manufactured', $request['home_type'] ),
                'isLand' => in_array( 'LotsLand', $request['home_type'] ),
                'isTownHome' => in_array( 'Townhomes', $request['home_type'] ),
                'minBed' => $request['bedsMinSale'] ?? null,
                'maxBed' => $request['bedsMaxSale'] ?? null,
                'minBath' => $request['bathsMinSale'] ?? null,
                'maxBath' => $request['bathsMaxSale'] ?? null
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
                'isHouse' => in_array( 'Houses', $request['home_type_rent'] ),
                'isCondo' => in_array( 'Apartments_Condos_Co-ops', $request['home_type_rent'] ),
                'isTownHome' => in_array( 'Townhomes', $request['home_type_rent'] ),
                'minBed' => $request['bedsMinRent'] ?? null,
                'maxBed' => $request['bedsMaxRent'] ?? null,
                'minBath' => $request['bathsMinRent'] ?? null,
                'maxBath' => $request['bathsMaxRent'] ?? null
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
