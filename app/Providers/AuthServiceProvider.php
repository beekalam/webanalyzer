<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Firebase\JWT\JWT;

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
            if ($request->input('jwt')) 
            {
                 try
                 {
                    $jwt = $request->input('jwt');
                    //todo: read from config
                    $key = "123";
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
