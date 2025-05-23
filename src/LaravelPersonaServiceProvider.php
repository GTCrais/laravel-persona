<?php

namespace GTCrais\LaravelPersona;

use GTCrais\LaravelPersona\Console\Commands\Install;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class LaravelPersonaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
		/* @see ResolvePersona::handle() */
		Request::macro('visitor', function() {
			return $this->_visitor;
		});

		Request::macro('visitorUuid', function() {
			return $this->_visitorUuid;
		});

		Request::macro('persona', function() {
			return $this->user() ?? $this->visitor();
		});
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
		$this->publishesMigrations([
			__DIR__ . '/../database/migrations' => database_path('migrations'),
		], 'laravel-persona-migrations');

		if ($this->app->runningInConsole()) {
			$this->commands([
				Install::class
			]);
		}

		$this->mergeMorphMap([
			'visitor' => 'Visitor'
		]);
    }

	protected function mergeMorphMap(array $map)
	{
		$current = Relation::morphMap() ?: [];

		// Merge and re-apply the morph map
		Relation::morphMap(array_merge($current, $map));
	}
}
