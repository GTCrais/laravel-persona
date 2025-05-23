<?php

namespace GTCrais\LaravelPersona\Http\Middleware;

use GTCrais\LaravelPersona\Http\Middleware\Concerns\InteractsWithVisitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePersonaExists
{
	use InteractsWithVisitor;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if (!$request->persona()) {
			$this->injectVisitorData(
				$request, $this->optionallyCreateVisitor()
			);
		}

        return $next($request);
    }
}
