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
            $token_detail = explode('/', Crypt::decrypt($token));

            if(time() > $token_detail[1]){
                return ['type' => 'fail', 'data' => 'token_invalid'];
            }else{
                $request->attributes->add(['user_id' => $token_detail[0]]);
            }

            return $next($request);
        }

        return ['type' => 'error', 'data' => '未登录'];
    }
}
