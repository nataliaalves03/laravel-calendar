<?php

namespace Nataliaalves\LaravelCalendar;

use Nataliaalves\LaravelCalendar\Exceptions\AuthException;
use Nataliaalves\LaravelCalendar\Services\Event;
use Illuminate\Support\Facades\Redirect;

class LaravelCalendarClass extends CalendarConnection
{
	public function __construct($config, $userId = null)
	{
		if (class_basename($config) === 'Application') {
			$config = $config['config'];
		}

		parent::__construct($config, $userId);
	}

	/**
	 * @return Event
	 * @throws AuthException
	 */
	public function event()
	{
		if (!$this->getToken()) {
			throw new AuthException('No credentials found.');
		}

		return new Event($this);
	}

	/**
	 * Returns the Calendar user email
	 *
	 * @return \Google_Service_Calendar_Profile
	 */
	public function user()
	{
		return $this->config('email');
	}

	/**
	 * Updates / sets the current userId for the service
	 *
	 * @return \Google_Service_Calendar_Profile
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;
		return $this;
	}

	public function redirect()
	{
		return Redirect::to($this->getAuthUrl());
	}

	/**
	 * Gets the URL to authorize the user
	 *
	 * @return string
	 */
	public function getAuthUrl()
	{
		return $this->createAuthUrl();
	}

	public function logout()
	{
		$this->revokeToken();
		$this->deleteAccessToken();
	}

}
