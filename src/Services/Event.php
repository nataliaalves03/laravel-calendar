<?php

namespace Nataliaalves\LaravelCalendar\Services;

use Nataliaalves\LaravelCalendar\LaravelCalendarClass;
use Nataliaalves\LaravelCalendar\Services\Event\Invite;
use Nataliaalves\LaravelCalendar\Traits\Filterable;
use Nataliaalves\LaravelCalendar\Traits\SendsParameters;
use Google_Service_Calendar;

class Event
{

	use SendsParameters,
		Filterable;

	public $service;

	public $preload = false;

	public $pageToken;

	public $client;

	/**
	 * Optional parameter for getting single and multiple emails
	 *
	 * @var array
	 */
	protected $params = [];

	/**
	 * Message constructor.
	 *
	 * @param  LaravelCalendarClass  $client
	 */
	public function __construct(LaravelCalendarClass $client)
	{
		$this->client = $client;
		$this->service = new Google_Service_Calendar($client);
	}

	/**
	 * Returns next page if available of messages or an empty collection
	 *
	 * @return \Illuminate\Support\Collection
	 * @throws \Google_Exception
	 */
	public function next()
	{
		if ($this->pageToken) {
			return $this->all($this->pageToken);
		} else {
			return new EventCollection([], $this);
		}
	}

	/**
	 * Returns a collection of Mail instances
	 *
	 * @param null|string $pageToken
	 *
	 * @return \Illuminate\Support\Collection
	 * @throws \Google_Exception
	 */
	public function all($pageToken = null)
	{
		if (!is_null($pageToken)) {
			$this->add($pageToken, 'pageToken');
		}

		$events = [];
		$response = $this->getEventsResponse();
		$this->pageToken = method_exists( $response, 'getNextPageToken' ) ? $response->getNextPageToken() : null;

		$allEvents = $response->getItems();

		if (!$this->preload) {
			foreach ($allEvents as $event) {
				$events[] = new Invite($event, $this->preload);
			}
		} else {
			$events = $this->batchRequest($allEvents);
		}

		$all = new EventCollection($events, $this);

		return $all;
	}

	/**
	 * Returns boolean if the page token variable is null or not
	 *
	 * @return bool
	 */
	public function hasNextPage()
	{
		return !!$this->pageToken;
	}

	/**
	 * Limit the messages coming from the queryxw
	 *
	 * @param  int  $number
	 *
	 * @return Event
	 */
	public function take($number)
	{
		$this->params['maxResults'] = abs((int) $number);

		return $this;
	}

	/**
	 * @param $id
	 *
	 * @return Invite
	 */
	public function get($id)
	{
		$event = $this->getRequest($id);

		return new Invite($event);
	}

	/**
	 * Creates a batch request to get all emails in a single call
	 *
	 * @param $allEvents
	 *
	 * @return array|null
	 */
	public function batchRequest($allEvents)
	{
		$this->client->setUseBatch(true);

		$batch = $this->service->createBatch();

		foreach ($allEvents as $key => $event) {
			$batch->add($this->getRequest($event->getId()), $key);
		}

		$eventsBatch = $batch->execute();

		$this->client->setUseBatch(false);

		$events = [];

		foreach ($eventsBatch as $event) {
			$events[] = new Invite($event);
		}

		return $events;
	}

	/**
	 * Preload the information on each Mail objects.
	 * If is not preload you will have to call the load method from the Mail class
	 * @return $this
	 * @see Mail::load()
	 *
	 */
	public function preload()
	{
		$this->preload = true;

		return $this;
	}

	public function getUser()
	{
		return $this->client->user();
	}

	/**
	 * @param $id
	 *
	 * @return \Google_Service_Calendar_Event
	 */
	private function getRequest($id)
	{
        return $this->service->events->get('primary', $id);
	}

	/**
	 * @return \Google_Service_Calendar_EventsResponse|object
	 * @throws \Google_Exception
	 */
	private function getEventsResponse()
	{
        $responseOrRequest = $this->service->events->listEvents('primary', $this->params);

		if ( get_class( $responseOrRequest ) === "GuzzleHttp\Psr7\Request" ) {
			$response = $this->service->getClient()->execute( $responseOrRequest, 'Google_Service_Calendar_EventResponse' );

			return $response;
		}

		return $responseOrRequest;
	}
}
