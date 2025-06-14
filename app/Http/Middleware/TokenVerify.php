<?php
namespace App\Http\Middleware;

use App\Helper\JwtToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        
// dd($request->cookie('token'));

        $decoded = JwtToken::verifyToken($token);
        if (is_object($decoded) && isset($decoded->user_id)) {
            $request->headers->set('user_id', $decoded->user_id);
            $request->headers->set('email', $decoded->email);
            return $next($request);
        }
        return response()->json(['message' => 'Invalid token'], 401);

    }

}
