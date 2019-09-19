<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager;

interface IRemoteAccessManager
{
	/** @var string */
	public const COOKIE_SECRET = 'ram-secret-key';

	/** @var bool */
	public const ALLOW_ALL = TRUE;

	/** @var bool */
	public const DENY_ALL = FALSE;

	/**
	 * @return void
	 */
	public function process(): void;
}
