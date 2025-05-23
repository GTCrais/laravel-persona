<?php

namespace GTCrais\LaravelPersona\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-persona:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs Laravel Persona package';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		if (file_exists(app_path('Models/Visitor.php'))) {
			$this->error('Visitor model already exists. Aborting.');

			return Command::FAILURE;
		}

        copy(__DIR__ . '/../../../stubs/Visitor.stub', app_path('Models/Visitor.php'));

		$this->info('Visitor model created..');

		Artisan::call('vendor:publish', ['--tag' => 'laravel-persona-migrations']);

		$this->info('Migration file published.');
		$this->info('Laravel Persona package installed successfully.');

		return Command::SUCCESS;
    }
}
