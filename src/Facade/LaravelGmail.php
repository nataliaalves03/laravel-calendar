<?php

namespace Nataliaalves\LaravelCalendar\Facade;

use Illuminate\Support\Facades\Facade;

class LaravelCalendar extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'laravelcalendar';
	}
}
