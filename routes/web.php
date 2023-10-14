<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Users;
use App\Http\Controllers\Ajax;
use App\Http\Controllers\Houses;
use App\Http\Controllers\Games;
use App\Http\Controllers\Tools;
use Illuminate\Support\Facades\DB;

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

Route::post( '/', [ Houses::class, 'index' ]);
Route::post( 'results', [ Houses::class, 'index' ]);
Route::post( 'reroll', [ Houses::class, 'reroll' ]);
Route::post( 'clicked-tab', [ Houses::class, 'clicked' ]);
Route::post( 'get-cities', [ Houses::class, 'citiesOptions' ]);

Route::post( 'new-home', [ Games::class, 'newGame' ]);
Route::post( 'check-result', [ Games::class, 'checkResult' ]);
Route::post( '/end-screen', [ Games::class, 'finalScore' ]);
Route::post( '/change-name', [ Games::class, 'changeName' ]);
Route::post( '/get-leaderboard', [ Games::class, 'changeLeaderboard' ]);

Route::post( '/get-daily', [ Games::class, 'getDaily' ]);
Route::post( '/check-daily', [ Games::class, 'checkDaily' ]);

$pages = [
	'/',
	'cuddleBunny',
	'results',
	'placethatprice',
	'calculator',
	'about-us',
	'privacy',
	'terms',
	'contact-us',
];
if(
	request()->isMethod('get')
	and !in_array( strtolower( request()->path() ), $pages )
	and strpos(request()->path(), 'calculator') !== 0
	and strpos(request()->path(), 'results') !== 0
	and strpos(request()->path(), 'placethatprice') !== 0
)
{
	redirect()->to('/')->send();
}

Route::get('/', [ Houses::class, 'index' ]);
Route::get( 'results{any}', [ Houses::class, 'result' ])->where('any', '.*');
Route::get('placethatprice', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.place',
			'body' => 'apps.placethatprice.startScreen',
			'description' => 'descriptions.place',
			'startIcon' => 'game',
		]
	);
});

Route::get('placethatprice/daily', [ Games::class, 'dailyStart' ] );

Route::get('placethatprice/competitive', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.place',
			'body' => 'apps.placethatprice.competitive',
			'description' => 'descriptions.place',
			'startIcon' => 'game',
		]
	);
});

Route::get('placethatprice/custom', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.place',
			'body' => 'apps.placethatprice.startScreen',
			'description' => 'descriptions.place',
			'startIcon' => 'game',
		]
	);
});

Route::get('placethatprice/leaderboard', [ Games::class, 'priceLeaderboard' ]);
Route::get( 'calculator{any}', [ Tools::class, 'calculatorState' ])->where('any', '.*');

Route::get('privacy', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.main',
			'body' => 'resources.privacyPolicy',
			'startIcon' => 'info',
		]
	);
});

Route::get('terms', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.main',
			'body' => 'resources.terms',
			'startIcon' => 'info',
		]
	);
});

Route::get('contact-us', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.main',
			'body' => 'resources.contact',
			'startIcon' => 'info',
		]
	);
});

Route::get('about-us', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.main',
			'body' => 'resources.about',
			'startIcon' => 'info',
		]
	);
});



Route::get('cuddleBunny', function () {
    return view('results');
});

