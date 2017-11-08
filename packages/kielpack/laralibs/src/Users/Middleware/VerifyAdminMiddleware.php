<?php
/**
 * Created by PhpStorm.
 * User: arnold.mercado
 * Date: 11/7/2017
 * Time: 4:59 PM
 */

namespace KielPack\LaraLibs\Users\Middleware;

use Closure;

class VerifyAdminMiddleware
{

    public function handle($request, Closure $next)
    {
        if($request->user() != null && $request->user()->isAdmin()) {

            return $next($request);

        }

        return abort(404,"Permision denied");
    }
}