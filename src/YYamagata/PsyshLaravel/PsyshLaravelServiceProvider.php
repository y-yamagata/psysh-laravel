<?php namespace YYamagata\PsyshLaravel;

use Illuminate\Support\ServiceProvider;
use YYamagata\PsyshLaravel\Console\PsyshCommand;

class PsyshLaravelServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('y-yamagata/psysh-laravel');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bindShared('command.psysh-laravel', function($app)
        {
            return new PsyshCommand($app);
        });
        $this->commands('command.psysh-laravel');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('command.psysh-laravel');
	}

}
