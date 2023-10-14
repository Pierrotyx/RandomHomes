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

class Tools extends Controller
{
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
}
