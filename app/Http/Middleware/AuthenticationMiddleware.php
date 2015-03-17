<?php namespace App\Http\Middleware;

use Auth;
use Closure;

class AuthenticationMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Auth::check()) {
			// User logged in
			return $next($request);
		} else {
			return response('Nem vagy bejelentkezve', 401);
		}
	}

}
