<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Calendar Configuration
	|--------------------------------------------------------------------------
	|
	|
	|
	|  Scopes Available:
	|
	|   * basic - Read, send, delete, and manage your email
	|   * compose - Manage drafts and send emails
	|
	|   Leaving the scopes empty fill use basic
	|
	|  Credentials File Name
	|
	*/

	'project_id' => env('GOOGLE_PROJECT_ID'),
	'client_id' => env('GOOGLE_CLIENT_ID'),
	'client_secret' => env('GOOGLE_CLIENT_SECRET'),
	'redirect_url' => env('GOOGLE_REDIRECT_URI', '/'),

	'state' => null,

	'scopes' => [
		'basic',
		'write',
	],

	/*
	|--------------------------------------------------------------------------
	| Additional Scopes [URL Style]
	|--------------------------------------------------------------------------
	|
	|   'additional_scopes' => [
	|        'https://www.googleapis.com/auth/drive',
	|        'https://www.googleapis.com/auth/documents'
	|   ],
	|
	|
	*/

	'additional_scopes' => [

	],

	'access_type' => 'offline',

	'approval_prompt' => 'force',

	/*
	|--------------------------------------------------------------------------
	| Credentials File Name
	|--------------------------------------------------------------------------
	|
	|   :email to use, clients email on the file
	|
	|
	*/

	'credentials_file_name' => env('GOOGLE_CREDENTIALS_NAME', 'calendar-json'),

	/*
	|--------------------------------------------------------------------------
	| Allow Multiple Credentials
	|--------------------------------------------------------------------------
	|
	|   Allow the application to store multiple credential json files.
	|
	|
	*/

	'allow_multiple_credentials' => env('GOOGLE_ALLOW_MULTIPLE_CREDENTIALS', false),

	/*
	|--------------------------------------------------------------------------
	| Allow Encryption for json Files
	|--------------------------------------------------------------------------
	|
	|   Use Laravel Encrypt in json Files
	|
	|
	*/

	'allow_json_encrypt' => env('GOOGLE_ALLOW_JSON_ENCRYPT', false),
];
