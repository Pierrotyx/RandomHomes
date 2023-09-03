<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Users;
use App\Http\Controllers\Ajax;
use App\Http\Controllers\Houses;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$GLOBALS['user'] = new Users();

Route::post( '/', [ Houses::class, 'index' ]);
Route::post( 'results', [ Houses::class, 'index' ]);
Route::post( 'new-home', [ Houses::class, 'newGame' ]);
Route::post( 'check-result', [ Houses::class, 'checkResult' ]);
Route::post( 'reroll', [ Houses::class, 'reroll' ]);
Route::post( 'clicked-tab', [ Houses::class, 'clicked' ]);

$pages = ['/', 'cuddleBunny', 'results', 'placethatprice', 'calculator', ];
if(
	request()->isMethod('get')
	and !in_array( strtolower( request()->path() ), $pages )
)
{
	redirect()->to('/')->send();
}

Route::get('/', [ Houses::class, 'index' ]);
Route::get('results', [ Houses::class, 'result' ]);
Route::get('placethatprice', function () {
    return view('homePrice');
});

Route::get('cuddleBunny', function () {
    return view('results');
});

