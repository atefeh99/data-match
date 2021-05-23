<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Level
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $level =  $request->administrative_level;
        $levels = array('provinces','counties','districts','rurals');
        if (!(in_array($level , $levels))) {
            return "administrative_level must be between in {'provinces','counties','districts','rurals'}";
        }
        return $next($request);
    }
}
