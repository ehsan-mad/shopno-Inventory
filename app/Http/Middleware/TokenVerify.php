<?php
namespace App\Http\Middleware;

use App\Helper\JwtToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

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

        if (!$decoded || !isset($decoded->user_id)) {
            return redirect()->route('login')
                ->with('error', 'Please login to continue');
        }

        // dd($decoded);

        // Set the authenticated user
        Auth::loginUsingId($decoded->user_id);

        // Set user ID and email in request headers for use in controllers
        $request->headers->set('user_id', $decoded->user_id);
        $request->headers->set('email', $decoded->email);

        return $next($request);
    }

}
