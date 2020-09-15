<?php

namespace Nataliaalves\LaravelCalendar\Services;

use Illuminate\Support\Collection;

class EventCollection extends Collection
{
	/**
	 * @var Event
	 */
	private $event;

	/**
	 * EventCollection constructor.
	 *
	 * @param Event $event
	 * @param array $items
	 */
	public function __construct( $items = [], Event $event = null )
	{
		parent::__construct( $items );
		$this->event = $event;
	}

	public function next()
	{
		return $this->event->next();
	}

	/**
	 * Returns boolean if the page token variable is null or not
	 *
	 * @return bool
	 */
	public function hasNextPage()
	{
		return !!$this->event->pageToken;
	}
}
