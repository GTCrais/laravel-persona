<?php

namespace GTCrais\LaravelPersona\Http\Middleware;

use GTCrais\LaravelPersona\Http\Middleware\Concerns\InteractsWithVisitor;
use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolvePersona
{
	use InteractsWithVisitor;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
		if (!$request->user()) {
			$visitor = Visitor::where('uuid', $this->visitorUuid())->first();
		}

		$this->injectVisitorData($request, $visitor ?? null);

		return $next($request);
    }
}
