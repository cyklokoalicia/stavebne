<?php namespace Monitor\src\Htmldom;

use Illuminate\Support\ServiceProvider;

class HtmldomServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		//* dont use
//		$this->package('yangqi/htmldom');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind('htmldom', function()
		{
			return new Htmldom;
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
}
