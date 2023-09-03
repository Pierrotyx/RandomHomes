<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Login;
use Illuminate\Support\Facades\DB;

class Users extends Controller
{
    public $id;
    public $name;

    public function __construct()
    {
        if( !empty( $_COOKIE['fantasyStockUser'] ) )
        {
            $user = DB::table( 'users' )
                ->select( '*' )
                ->where( 'userHashCode', '=', $_COOKIE['fantasyStockUser'] )
                ->where( 'userDeleted', '=', false )
                ->first()
            ;
            if( empty( $user ) )
            {
                Login::logoutAjax();
            }
            else
            {
                $this->id = $user->userId;
                $this->name = $user->userName;
            }
        }
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }
}
