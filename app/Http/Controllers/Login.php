<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Login extends Controller
{
    public function getLoginScreen()
    {
        return
            view( 'loginScreen' );
        ;
    }
    
    public static function createAccount( $request )
    {
        $errorMsg = '';
        foreach( $request as $key => $val )
        {
            if( empty( trim( $val ) ) )
            {
                $errorMsg .= '<ul>' . $key . ' is empty!</ul>';
            }
            else
            {
                $tmpError = '';
                switch( $key )
                {
                    case 'Username':
                        if( !( $tmpError .= self::checkLength( $val, 'Username', 4, 16 ) ) )
                        {
                            $tmpError .= self::checkIfExists( $val, 'username', 'userName' );
                        }
                        break;
                    case 'Email':
                        if( !( $tmpError .= ( !filter_var( $val, FILTER_VALIDATE_EMAIL ) ? '<ul>This is not a valid email</ul>' : '' ) ) )
                        {
                            $tmpError .= self::checkIfExists( $val, 'email', 'userEmail' );
                        }
                        break;
                    case 'Birthday':
                        if( ( strtotime( date( 'Y-m-d' ) ) - strtotime( $val ) ) / 60 / 60 / 24/ 365 < 13 )
                        {
                            $tmpError .= '<ul>Sorry, this game is for people above 13 years old</ul>';
                        }
                        break;
                    case 'Password':
                        if( !( $tmpError .= self::checkLength( $val, 'Password', 6, 40 ) ))
                        {
                            if( $val != $request['Check'] )
                            {
                                $tmpError .= '<ul>Please make sure your passwords are the same</ul>';
                            }
                        }
                        break;
                }
                $errorMsg .= $tmpError;
            }
            
            if( $key == 'Password' )
            {
                break;
            }
        }
        if( !empty( $errorMsg ) )
        {
            return '<li>' . $errorMsg . '</li>';
        }
        else
        {
            DB::table( 'users' )->insert([
                'userDeleted' => 0,
                'userName' => $request['Username'],
                'userPassword' => password_hash( $request['Password'], PASSWORD_DEFAULT ),
                'userEmail' => $request['Email'],
                'userBirthDate' => date( 'Y-m-d', strtotime( $request['Birthday'] ) ),
            ]);
            return '';
        }
    }
    
    public function loginAjax( Request $request )
    {
        $login = self::checkLogin( $request );
        return $login ? view( 'profile' )->render() : false;
    }
    
    static public function logoutAjax()
    {
        setcookie( 'fantasyStockUser', '', time() - 1000, '/' );
        return view( 'loginScreen' )->render();
    }
    
    private function checkLogin(  Request $request  )
    {
        $username = $request->username;
        $sql = '
            SELECT
                userId,
                userPassword
            FROM
                users
            WHERE 
                LOWER(userName) = LOWER("' . $username . '")
                OR LOWER(userEmail) = LOWER("' . $username . '")
            LIMIT 1
            ;
        ';
        
        $user = DB::select( $sql )[0];

        if( password_verify( $request->password, $user->userPassword ) )
        {
            $cookieName = 'fantasyStockUser';
            $hashCode = hash( 'sha256', rand() );
            DB::table( 'users' )
                ->where( 'userId', '=', $user->userId )
                ->update( [
                    'userHashCode' => $hashCode
                ] )
            ;
            setcookie( $cookieName, $hashCode, time() + ( 86400 * 30 ), '/' ); // 86400 = 1 day
            return true;
        }
        return false;
    }
    
    private static function checkLength( $text, $name, $min, $max )
    {
        if( strlen( $text ) < $min )
        {
            return '<ul>' . $name . ' needs to be at least ' . $min . ' letters</ul>';
        }
        elseif( strlen( $text ) > $max )
        {
            return '<ul>' . $name . ' needs to be no longer than ' . $max . ' letters</ul>';
        }
        return '';      
    }
    
    private static function checkIfExists( $text, $name, $column )
    {
        $sql = '
            SELECT
                *
            FROM
                users
            WHERE 
                LOWER(' . $column . ') = LOWER("' . $text . '")
                AND userDeleted = 0
            LIMIT 1
            ;
        ';
        
        $data = DB::select( $sql );
        
        if( !empty( $data ) )
        {
            return '<ul>This ' . $name . ' already exists, please use a different ' . $name . '</ul>';
        }
        return '';
    }
}

