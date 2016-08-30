<?php

namespace App\Providers;

use App\User;
use App\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Firebase\JWT\JWT;
use App\JWTAuthenticate;
use Log as llog;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        Auth::viaRequest('api', function ($request) {
            // llog::info("=======================[boot/]");
            if ($request->has('jwt')) 
            {
                // llog::info("======================[boot/2]");
                 try
                 {
                    $jwt = $request->input('jwt');
                    $key = env("APP_KEY");
                    $algorithm = ['HS256'];
                    $token = JWT::decode($jwt, $key,$algorithm);
                    return ['jwt' => $jwt];
                 }
                 catch(Exception $e)
                 {
                    return null;
                 }
            }
            return null;
        });
    }
}
