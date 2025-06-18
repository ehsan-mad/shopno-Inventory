<?php
namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken
{

    public static function generateToken($user_id, $email)
    {
        $key = env('jwt_key');

        $payload = [
            'user_id' => $user_id,
            'email'   => $email,
            'iat'     => time(), // Issued at
            'exp'     => time() + (60 * 60 *24),
            'iss'     => 'user',

        ];
        $token = JWT::encode($payload, $key, 'HS256');
        return $token;

    }

    public static function verifyToken($token)
    {
        $key = env('jwt_key');
        try {
            if (! $token) {
                return null;
            }

            $decode = JWT::decode($token, new Key($key, 'HS256'));
           
            return $decode;
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function resetPasswordToken($email)
    {
        $key     = env('jwt_key');
        $payload = [
            'email' => $email,

            'iat'   => time(),             // Issued at
            'exp'   => time() + (60 * 60), // Expiration time
            'iss'   => 'password_reset',
        ];
        $resetToken = JWT::encode($payload, $key, 'HS256');

        return $resetToken;
    }

}
