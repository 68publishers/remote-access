<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager;

interface IRemoteAccessManager
{
	const   COOKIE_SECRET = 'ram-secret-key';

	const   ALLOW_ALL = TRUE,
			DENY_ALL = FALSE;

	/**
	 * @return void
	 */
	public function process(): void;
}
