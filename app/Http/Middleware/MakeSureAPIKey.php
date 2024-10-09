<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MakeSureAPIKey
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
        $clientKey = $request->header('client-key');
        $client = DB::table('sanctum_clients')->where('client_key', $clientKey);
        if($client->count() == 0){
            return response()->json([
                'status'    => 'error',
                'message'   => 'Client key tidak valid'
            ], 400);
        }
        return $next($request);
    }
}
