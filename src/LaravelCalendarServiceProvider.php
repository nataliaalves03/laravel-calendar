<?php

namespace Nataliaalves\LaravelCalendar;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class LaravelCalendarServiceProvider extends ServiceProvider
{

	public function boot()
	{
		$this->publishes([__DIR__.'/config/calendar.php' => App::make('path.config').'/calendar.php',]);
	}

	public function register()
	{

		$this->mergeConfigFrom(__DIR__.'/config/calendar.php', 'calendar');

		// Main Service
		$this->app->bind('laravelcalendar', function ($app) {
			return new LaravelCalendarClass($app['config']);
		});

	}
}
