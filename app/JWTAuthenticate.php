<?php

namespace App;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\InvalidArgumentException;
use App\SqliteDB;


class JWTAuthenticate
{
    private $usrename;
    private $password;
    private $algorithm = 'HS256';
    private $timeout = 1200;
    private $servarname;
    private $secretkey;
    private $sqlitedb;
    function __construct($username = '', $password = '',$servername='')
    {
        $this->username = $username;
        $this->password = $password;
        $this->servername = $servername;
        $this->secretkey = env('APP_KEY');
        $this->sqlitedb = new SqliteDB();
    }

    public function isAuthorized()
    {
        if ($this->userPassOk())
        {
            //if($this->username === 'admin' && $this->password === 'admin')
            if($this->sqlitedb->authenticateUser($this->username, $this->password) === true)
            {
                return true;
            }
        }

        return false;
    }

    public function makeJWTTag()
    {
            $tokenId     = base64_encode(123);
            $issuedAt    = time();
            $notBefore   = $issuedAt + 10;  //10 seconds
            // $expire      = $notBefore + 60;  //60 seconds
            $expire = time() + $this->timeout;
            $jwt_data =[
                'iat' => $issuedAt,
                'jti' => $tokenId,
                'iss' => $this->servername,
                // 'nbf' => $notBefore,
                'exp' => $expire,
                'data' => [
                    'userId' => 'userid----',             //@userid
                    'username' => $this->username,       //@username
                ]
            ];
            $jwt = JWT::encode($jwt_data, $this->secretkey, $this->algorithm);
            return ['jwt' => $jwt];
    }

    public  function checkJWT($jwt)
    {
        // try
        // {
        //     //$key = env("APP_KEY");
        //     //$algorithm = ['HS256'];
        //     $token = JWT::decode($jwt, $this->secretkey,[$this->algorithm]);
        // }
        // catch(SignatureInvalidException $e)
        // {

        // }
        // catch(ExpiredException $e)
        // {

        // }
            throw new Exception("not implemented");
    }

    private function userPassOk()
    {
        return (! $this->isNullOrEmpty($this->username)) && (! $this->isNullOrEmpty($this->password));
    }

    private function isNullOrEmpty($var)
    {
        return (is_null($var)) || (strlen($var) === 0);
    }
}
