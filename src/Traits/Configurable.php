<?php

namespace Nataliaalves\LaravelCalendar\Traits;

use Google_Service_Calendar;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;

/**
 * Trait Configurable
 * @package Nataliaalves\LaravelCalendar\Traits
 */
trait Configurable
{

	protected $additionalScopes = [];
	private $_config;

	public function __construct($config)
	{
		$this->_config = $config;
	}

	public function config($string = null)
	{
		$disk = Storage::disk('local');
		$fileName = $this->getFileName();
		$file = "calendar/tokens/$fileName.json";
		$allowJsonEncrypt = $this->_config['calendar.allow_json_encrypt'];

		if ($disk->exists($file)) {
			if ($allowJsonEncrypt) {
				$config = json_decode(decrypt($disk->get($file)), true);
			} else {
				$config = json_decode($disk->get($file), true);
			}

			if ($string) {
				if (isset($config[$string])) {
					return $config[$string];
				}
			} else {
				return $config;
			}

		}

		return null;
	}

	private function getFileName()
	{
		if (property_exists(get_class($this), 'userId') && $this->userId) {
			$userId = $this->userId;
		} elseif (auth()->user()) {
			$userId = auth()->user()->id;
		}

		$credentialFilename = $this->_config['calendar.credentials_file_name'];
		$allowMultipleCredentials = $this->_config['calendar.allow_multiple_credentials'];

		if (isset($userId) && $allowMultipleCredentials) {
			return sprintf('%s-%s', $credentialFilename, $userId);
		}

		return $credentialFilename;
	}

	/**
	 * @return array
	 */
	public function getConfigs()
	{
		return [
			'client_secret' => $this->_config['calendar.client_secret'],
			'client_id' => $this->_config['calendar.client_id'],
			'redirect_uri' => url($this->_config['calendar.redirect_url']),
			'state' => isset($this->_config['state']) ? $this->_config['state'] : null,
		];
	}

	public function setAdditionalScopes(array $scopes)
	{
		$this->additionalScopes = $scopes;

		return $this;
	}

	private function configApi()
	{
		$type = $this->_config['calendar.access_type'];
		$approval_prompt = $this->_config['calendar.approval_prompt'];

		$this->setScopes($this->getUserScopes());

		$this->setAccessType($type);

		$this->setApprovalPrompt($approval_prompt);
	}

	public abstract function setScopes($scopes);

	private function getUserScopes()
	{
		return $this->mapScopes();
	}

	private function mapScopes()
	{
		$scopes = array_merge($this->_config['calendar.scopes'] ?? [], $this->additionalScopes);
		$scopes = array_unique(array_filter($scopes));
		$mappedScopes = [];

		if (!empty($scopes)) {
			foreach ($scopes as $scope) {
				$mappedScopes[] = $this->scopeMap($scope);
			}
		}

		return array_merge($mappedScopes, $this->_config['calendar.additional_scopes'] ?? []);
	}

	private function scopeMap($scope)
	{
		$scopes = [
			'basic' => Google_Service_Calendar::CALENDAR,
			'write' => Google_Service_Calendar::CALENDAR_EVENTS,
			'read' => Google_Service_Calendar::CALENDAR_EVENTS_READONLY,
			'readonly' => Google_Service_Calendar::CALENDAR_READONLY,
			'settings_basic' => Google_Service_Calendar::CALENDAR_SETTINGS_READONLY
		];

		return Arr::get($scopes, $scope);
	}

	public abstract function setAccessType($type);

	public abstract function setApprovalPrompt($approval);

}
