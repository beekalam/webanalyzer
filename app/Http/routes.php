<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
$app->get('/', function () use ($app) {
    return view('test');
});


$app->get('/logs/{page}','LogsController@showLogs');
$app->get('/logdetails/{username}/{page:[\d]+}', 'LogsController@showLogdetails');
$app->get('/weblogs/{connection_log_id}/{page}', 'LogsController@showWebLogs');
// $app->get('/logs/{user_id:[\d]+}/{page}', 'LogsController@showLogs');
// $app->get('/logs/{user_name}/{page}', 'LogsController@showWebLogs');
$app->get('/post/{id}', ['middleware' => 'auth', function (Request $request, $id) {
    $user = Auth::user();

    $user = $request->user();
    return var_dump($user);
    //
}]);