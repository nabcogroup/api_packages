<?php

namespace KielPack\LaraLibs\Users\Middleware;

use Closure;

class RoleAuthMiddleware
{

    public function handle($request, Closure $next)
    {
        if($request->user() === null) {
            return abort(401, 'Unauthorized access');
        }

        $actions = $request->route()->getAction();
        $roles = isset($actions['roles']) ? $actions['roles'] : null;
        if(!$roles || $request->user()->hasAnyRole($roles)) {
            return $next($request);
        }

        return abort(401, 'Unauthorized access');
    }
}