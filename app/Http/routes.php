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
use Illuminate\Http\Request;
use \App\JWTAuthenticate;
use \Firebase\JWT\JWT;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Log;
use Log as llog;
$app->get('/', function () use ($app) {
    return view('test');
});

$app->get('/logs/{page:[\d]+}',[ 
    // 'middleware' => 'auth',
    'uses' =>'LogsController@showAllLogs'
]);

$app->get('/logs/{username}/{page:[\d]+}', [ 
    // 'middleware' => 'auth',
    'uses' => 'LogsController@showLogs'
]);

$app->get('/logs/{username}/{startdate}/{enddate}/{page}',[
    // 'middleware' => 'auth',
    'uses' => 'LogsController@showLogsByDate'
]);

$app->get('/nases/',[
    // 'middleware' => 'auth',
    'uses' => 'LogsController@showNases'
]);

$app->post('/nases/add',[
    // 'middleware' => 'auth',
    'uses' => 'LogsController@addNas'
]);

$app->get('/nases/delete/{id:[\d]+}',[
    // 'middleware' => 'auth',
    'uses' => 'LogsController@deleteNas'
]);

$app->get('/rules/' , [
    // 'middleware' => 'auth',
    'uses' => 'LogsController@getRules'
]);

$app->post('/rules/', [
    // 'middleware' => 'auth',
    'uses' => 'LogsController@createRule'
]);

$app->get('/rules/delete/{id:[\d]+}', [
    // 'middleware' => 'auth',
    'uses' => 'LogsController@deleteRule'
]);

// $app->get('/weblogs/{connection_log_id}/{page}', [
//     // 'middleware' =>'auth',
//      'uses' => 'LogsController@showWebLogs'
// ]);

// $app->get('/logs/{user_id:[\d]+}/{page}', 'LogsController@showLogs');
// $app->get('/logs/{user_name}/{page}', 'LogsController@showWebLogs');



$app->post('/token', function(Request $request){
    llog::info("=========================[/token]");
    //fixme return 401 on timeout
    $username = $request->input('username');
    $password = $request->input('password');
    $servername = $request->server('SERVER_NAME');
    $jwtauthenticate = new JWTAuthenticate($username, $password, $servername);

    if ($jwtauthenticate->isAuthorized())
    {
        return $jwtauthenticate->makeJWTTag();
    }

    return response('unauthorized')->setStatusCode('401');
});
