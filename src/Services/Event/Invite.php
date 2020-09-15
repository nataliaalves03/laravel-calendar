<?php

namespace Nataliaalves\LaravelCalendar\Services\Event;

use Carbon\Carbon;
use Nataliaalves\LaravelCalendar\CalendarConnection;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Collection;

/**
 * Class SingleMessage
 *
 * @package Nataliaalves\LaravelCalendar\services
 */
class Invite extends CalendarConnection
{

	/**
	 * @var
	 */
	public $id;

	/**
	 * @var
	 */
	public $summary;

	/**
	 * @var
	 */
	public $description;

	/**
	 * @var Google_Service_Calendar
	 */
	public $service;

	/**
	 * SingleMessage constructor.
	 *
	 * @param \Google_Service_Calendar_Event $message
	 * @param bool $preload
	 * @param  int 	$userId
	 */
	public function __construct(\Google_Service_Calendar_Event $message = null, $preload = false, $userId = null)
	{
		$this->service = new Google_Service_Calendar($this);

		parent::__construct(config(), $userId);

		if (!is_null($message)) {
			$this->setMessage($message);
		}
	}

	/**
	 * Sets data from mail
	 *
	 * @param \Google_Service_Calendar_Event $message
	 */
	protected function setMessage(\Google_Service_Calendar_Event $message)
	{
		$this->id = $message->getId();
		$this->summary = $message->getSummary();
		$this->description = $message->getDescription();
	}

	/**
	 * Returns ID of the email
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Returns ID of the email
	 *
	 * @return string
	 */
	public function getSummary()
	{
		return $this->summary;
	}

	/**
	 * Returns ID of the email
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Gets the user email from the config file
	 *
	 * @return mixed|null
	 */
	public function getUser()
	{
		return $this->config('email');
	}

	/**
	 * Sets the access token in case we wanna use a different token
	 *
	 * @param string $token
	 *
	 * @return Mail
	 */
	public function using($token)
	{
		$this->setToken($token);

		return $this;
	}

}
