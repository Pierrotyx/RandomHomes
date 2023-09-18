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

Route::post( '/', [ Houses::class, 'index' ]);
Route::post( 'results', [ Houses::class, 'index' ]);
Route::post( 'new-home', [ Houses::class, 'newGame' ]);
Route::post( 'check-result', [ Houses::class, 'checkResult' ]);
Route::post( 'reroll', [ Houses::class, 'reroll' ]);
Route::post( 'clicked-tab', [ Houses::class, 'clicked' ]);
Route::post( 'get-cities', [ Houses::class, 'citiesOptions' ]);
Route::post( '/end-screen', [ Houses::class, 'finalScore' ]);
Route::post( '/change-name', [ Houses::class, 'changeName' ]);

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
	'placethatprice/leaderboard',
];
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
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.price',
			'body' => 'apps.placethatprice.startScreen',
			'startIcon' => 'game',
		]
	);
});

Route::get('placethatprice/leaderboard', [ Houses::class, 'priceLeaderboard' ]);

Route::get('privacy', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.home',
			'body' => 'resources.privacyPolicy',
			'startIcon' => 'info',
		]
	);
});

Route::get('terms', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.home',
			'body' => 'resources.terms',
			'startIcon' => 'info',
		]
	);
});

Route::get('contact-us', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.home',
			'body' => 'resources.contact',
			'startIcon' => 'info',
		]
	);
});

Route::get('about-us', function () {
    return view(
		'randomHouse',
		[
			'head' => 'templates.heads.home',
			'body' => 'resources.about',
			'startIcon' => 'info',
		]
	);
});

Route::get('calculator', function () {
    return view('apps.calculator.main');
});

Route::get('cuddleBunny', function () {
    return view('results');
});

