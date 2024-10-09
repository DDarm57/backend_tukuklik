<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionTokodaring
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->get('nonce') !== null){
            $lpseAccount = DB::table('lpse_account')->where('token', $request->get('nonce'));
            if($lpseAccount->count() > 0){
                $lpseAccount = $lpseAccount->first();
                $user = User::where('username_lpse', $lpseAccount->username)->first();
                if(!Auth::check()){
                    Auth::login($user);
                }
            }else{
                return response()->json([
                    'code'      => '200',
                    'data'      => null,
                    'message'   => 'LPSE Account not found',
                    'status'    => false 
                ]);
            }
        }else{
       		if($request->headers->get('referer') !== null){
                $urlReferer =  parse_url($request->headers->get('referer'));
            	if(isset($urlReferer['query'])){
                   	parse_str($urlReferer['query'], $query);
                	if($request->isMethod('get')){
                    	if(isset($query['nonce'])){
                            if($query['nonce'] !== ''){
                                return redirect($request->fullUrlWithQuery(['nonce' => $query['nonce']]));
                            }
                        }
                    }
                }
            }
        }
        return $next($request);
    }
}
