<?php namespace YYamagata\LaravelPsysh;

use Illuminate\Support\ServiceProvider;
use YYamagata\LaravelPsysh\Console\PsyshCommand;

class LaravelPsyshServiceProvider extends ServiceProvider {

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
		$this->package('y-yamagata/laravel-psysh');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bindShared('command.laravel-psysh', function($app)
        {
            return new PsyshCommand($app);
        });
        $this->commands('command.laravel-psysh');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('command.laravel-psysh');
	}

}
