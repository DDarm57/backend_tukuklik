<?php

namespace App\Http\Middleware;

use App\Services\RolePermissionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests as Authorize;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use Authorize;

    public function handle(Request $request, Closure $next)
    {
        if(!$request->ajax() && !in_array(explode('.',$request->route()->action['as'])[0], $this->exception())){
            $permissionStatus = RolePermissionService::validatePermission($request->route()->action['as'], $request->route()->methods[0]);
            $permissionStatus ? null : $this->authorize($permissionStatus);
        }
        return $next($request);
    }

    protected function exception()
    {
        return [
            'profile'
        ];
    }
}
