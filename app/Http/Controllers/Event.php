<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Event extends Controller
{
    public function displayGraph( Request $request )
    {
        return
            view( 'index',
                [
                    'startTime' => 'getDay',
                    'startCompany' => 1,
                    'allCompanies' => Self::getAllSites(),
                    'login' => view( 'loginScreen' )
                ]
            )
        ;
    }
    
    static public function getRandomEvent()
    {
        $data = DB::table( 'events' )
            ->leftJoin( 'voteHistory', function( $join )
                 {
                     $join->on( 'voteHistory.eventId', '=', 'events.eventId' );
                 }
            )
            ->where( 'events.userId', '!=', $GLOBALS['user']->getId() )
            ->whereNull( 'voteHistory.voteHistoryId')
            ->orderBy( DB::raw( 'RAND()' ) )
            ->take( 1 )
            ->get();
        return print_r($data);
    }
    
    static public function getStockData( $length, $company )
    {
        $allData = DB::table( 'stocks' )
            ->select( 'stockTime', 'stockAmount' )
            ->where( 'companyId', '=', $company )
            ->take( $length )
            ->orderBy( 'stockId', 'DESC' )
            ->get()
        ;
        
        $companyName = DB::table( 'companies' )
            ->select( 'companyName' )
            ->where( 'companyId', '=', $company )
            ->first()
            ->companyName
        ;
        
        $dayLength = 144;
        $displayType = 'Days';
        $title = $companyName . ' Stocks ';
        $toolFormat = 'm/d/y h:i a';
        $dateSub = 'value.substr( 0, 10 )';
        $dateFormat = 'm/d/y h:i a';
        $stockAverageTime = 1;
        switch( $length )
        {
            case $dayLength - 1:
                $title .= '(1 DAY)';
                $dateSub = '( value.substr( 9, 2 ).substr(0,1) == 0 ? value.substr( 10, 1 ) : value.substr( 9, 2 ) ) + value.substr( 14, 3 )';
                $interval = 6;
                $displayType = 'Hours';
                break;
            case $dayLength * 7:
                $title .= '(1 WEEK)';
                $dateSub = 'value.substr( 0, 5 )';
                $interval = 145;
                $displayType = 'Days';
                break;
            case $dayLength * 30:
                $title .= '(1 MONTH)';
                $dateSub = 'value.substr( 0, 5 )';
                $interval = 24;
                $displayType = 'Days';
                $stockAverageTime = $dayLength / 24;
                break;
            case $dayLength * 30 * 3:
                $title .= '(3 MONTH)';
                $dateSub = 'value.substr( 0, 5 )';
                $interval = 31;
                $displayType = 'Days';
                $stockAverageTime = $dayLength / 4;
                break;
            case $dayLength * 365:
                $dateSub = 'value.substr( 0, 3 ) + value.substr( 6, 2 ) ';
                $title .= '(1 YEAR)';
                $displayType = 'Months';
                $interval = 31;
                $toolFormat = 'm/d/y h a';
                $stockAverageTime = $dayLength;
                break;
            case $dayLength * 365 * 5:
                $dateSub = 'value.substr( 0, 8 )';
                $dateFormat = 'M/Y';
                $displayType = 'Months';
                $title .= '(5 YEAR)';
                $interval = 25;
                $stockAverageTime = $dayLength * 5;
                break;
        }
        
        $data = collect();
        $currentTime = $allData[0]->stockTime;
        $allDataLength = count( $allData );
        $firstTime = $allData[ $allDataLength - 1 ]->stockTime;
        $allData = $allData->chunk( $stockAverageTime );
        $allData = $allData->reverse();
        foreach( $allData as $stocks )
        {
            $data->push( ['stockAmount' => $stocks->avg( 'stockAmount' ), 'stockTime' => $stocks->first()->stockTime] );
            $currentTime -= $stockAverageTime;
        }
        
        $start = $data[0]['stockAmount'];
        $min = $data->min( 'stockAmount' );
        $max = $data->max( 'stockAmount' );
        $diff = max( $start - $min, $max - $start ) * 1.05;
        $leftOver = $length / $stockAverageTime - count($data);
        while( $leftOver >= 0 )
        {
            $tempData = $data[0];
            $tempData['stockTime'] -= $stockAverageTime;
            $data->prepend( $tempData );
            $leftOver--;
        }
        
        $data = json_decode( json_encode ( $data ), true );
        
        foreach( $data as &$val )
        {
            $dateInt = ( $val['stockTime'] * 3600 / ( $dayLength / 24 ) ) - 62167219200;
            $val['dateTime'] = date( 'm/d/y h:i a', $dateInt );
            $val['tipToolDate'] = date( $toolFormat, $dateInt );
            $val['stockDisplay'] = $val['dateTime'];
        }
        unset( $val );

        return [
            'data' => $data,
            'interval' => $interval,
            'title' => $title,
            'displayType' => $displayType,
            'companyName' => $companyName,
            'dateSub' => $dateSub,
            'limits' => [ floor( $start - $diff ), $start, ceil( $start + $diff ) ]
        ];
    }
}
