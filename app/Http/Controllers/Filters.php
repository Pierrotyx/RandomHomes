<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Python;
use App\Models\Parameter;

class Filters extends Controller
{
    public static function getFilters( $homeId )
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

    public static function getTranslatedFilters( $homeId )
    {
        return self::translateFiltersFromDb( self::getFilters( $homeId ) );
    }

    public static function translateFiltersFromDb( $filters )
    {
        $homeType = [];
        if( $filters['type'] == 'ForRent' )
        {
            $translateSheet = [
                'type' => 'type',
                'minPrice' => 'minrp',
                'MaxPrice' => 'maxrp',
                'state' => 'state-rent',
                'city' => 'city-rent',
                'minBed' => 'bedsMinRent',
                'maxBed' => 'bedsMaxRent',
                'minBath' => 'bathsMinRent',
                'maxBath' => 'bathsMaxRent',
                'minLiving' => 'livingMinRent',
                'maxLiving' => 'livingMaxRent',
                'minLot' => 'lotMinRent',
                'maxLot' => 'lotMaxRent',
            ];

            $homeTypeName = 'home_type_rent';
            $homeTypeTranslate = [
                'isHouse'    => 'Houses',
                'isCondo'    => 'Apartments_Condos_Co-ops',
                'isTownHome' => 'Townhomes'
            ];
        }
        else
        {
            $translateSheet = [
                'type' => 'type',
                'minPrice' => 'minsp',
                'MaxPrice' => 'maxsp',
                'state' => 'state-sale',
                'city' => 'city-sale',
                'minBed' => 'bedsMinSale',
                'maxBed' => 'bedsMaxSale',
                'minBath' => 'bathsMinSale',
                'maxBath' => 'bathsMaxSale',
                'minLiving' => 'livingMinSale',
                'maxLiving' => 'livingMaxSale',
                'minLot' => 'lotMinSale',
                'maxLot' => 'lotMaxSale',
            ];

            $homeTypeName = 'home_type';
            $homeTypeTranslate = [
                'isHouse'        => 'Houses',
                'isCondo'        => 'Condos',
                'isMulti'        => 'Multi-family',
                'isManufactured' => 'Manufactured',
                'isLand'         => 'LotsLand',
                'isTownHome'     => 'Townhomes'
            ];
        }
        $newFilters = [ 'type' => $filters['type'], $homeTypeName => [ '' ] ];
        foreach( $translateSheet as $key => $transaltePart )
        {
            if( !empty( $filters[ $key ] ) )
            {
                $newFilters[ $transaltePart ] = $filters[ $key ];
            }
        }

        foreach( $homeTypeTranslate as $key => $homeType )
        {
            if( !empty( $filters[ $key ] ) )
            {
                $newFilters[ $homeTypeName ][] = $homeType;
            }
        }

        return $newFilters;
    }
}
