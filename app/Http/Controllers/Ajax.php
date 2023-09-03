<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Login;
use App\Http\Controllers\Event;

class Ajax extends Controller
{

    public function getProfile( Request $request )
    {
        return view( !empty( $_COOKIE['fantasyStockUser'] ) ? 'profile': 'loginScreen' )->render();
    }
    
    static public function getEvent()
    {
        return view( 'event', [ 'events' => Event::getRandomEvent() ] )->render();
    }
    
    public function getCreateEvent( Request $request )
    {
        return view( 'createEvent' )->render();
    }
    
    public function getRegister( Request $request )
    {
        return view( 'createUser' )->render();
    }
    
    public function postRegister( Request $request )
    {
        return Login::createAccount( $request->all() );
    }
    
    public function getUserStocks( Request $request )
    {
        return view( !empty( $_COOKIE['fantasyStockUser'] ) ? 'profile': 'loginScreen' )->render();
    }
    
    public function getRandomForRent( Request $request )
    {
        return view( !empty( $_COOKIE['fantasyStockUser'] ) ? 'profile': 'loginScreen' )->render();
    }
}
