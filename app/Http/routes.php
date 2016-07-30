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
use \Firebase\JWT\JWT;

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

$app->post('/token', function(Request $request){

    $username = $request->input('username');
    $password = $request->input('password');
    
    //@check username and password here later
	if($username == "admin" && $password=="admin"){
            //@ make jwt string
            $tokenId     = base64_encode(123);
            $issuedAt    = time();
            $notBefore   = $issuedAt + 10;  //10 seconds
            // $expire      = $notBefore + 60;  //60 seconds
            $expire = time() + 100;
            $serverName  = $request->server('SERVER_NAME');
            $jwt_data =[
                'iat' => $issuedAt,
                'jti' => $tokenId,
                'iss' => $serverName,
                // 'nbf' => $notBefore,
                'exp' => $expire,
                'data' => [
                    'userId' => 'userid----',       //@userid
                    'username' => 'username',       //@username
                ]
            ];
            $secretKey = "123";                     //@use generated secretkey
            $algorithm = 'HS256';                   //@get from config file
            $jwt = JWT::encode($jwt_data, $secretKey, $algorithm);
            return ['jwt' => $jwt];
    }
    return "not authorized";
});