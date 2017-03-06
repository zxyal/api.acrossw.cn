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

        if($token){
            try {
                $token_detail = explode('/', Crypt::decrypt($token));

                if(time() < $token_detail[1]){
                    return ['type' => 'fail', 'data' => 'token_invalid'];
                }else{
                    $request->attributes->add(['user_id' => $token_detail[0]]);
                }
            } catch (DecryptException $e) {
                return ['type' => 'fail', 'data' => 'token_invalid'];
            }
        }

        return $next($request);
    }
}
