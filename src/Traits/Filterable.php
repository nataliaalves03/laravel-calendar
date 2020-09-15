<?php

namespace Nataliaalves\LaravelCalendar\Traits;

use Nataliaalves\LaravelCalendar\Services\Event;

trait Filterable
{
	
	public abstract function add( $query, $column = 'q', $encode = true );


	/**
	 * Filter to get only emails after a specific date
	 *
	 * @param $date
	 *
	 * @return self|Event
	 */
	public function after( $date )
	{
		$this->add( "after:{$date}" );

		return $this;
	}

	/**
	 * Filter to get only emails before a specific date
	 *
	 * @param $date
	 *
	 * @return self|Event
	 */
	public function before( $date )
	{
		$this->add( "before:{$date}" );

		return $this;
	}

	
}
