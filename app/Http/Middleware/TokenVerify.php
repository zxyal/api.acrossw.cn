<?php

namespace App\Http\Middleware;

use Closure;
use Crypt;

class TokenVerify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('Verify-Token');

        $response = $next($request);

        if($token){
            try {
                $token_detail = explode('/', Crypt::decrypt($token));

                if(time() < $token_detail[1]){
                    $response->header('Verify-Token', 'invalid');
                }else{
                    $request->attributes->add(['user_id' => $token_detail[0]]);
                    $response = $next($request);
                }
            } catch (DecryptException $e) {
                $response->header('Verify-Token', 'invalid');
            }
        }

        return $response;
    }
}
