<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager\Handler;

interface IAccessHandler
{
	/**
	 * @return void
	 */
	public function allow(): void;

	/**
	 * @return void
	 */
	public function deny(): void;
}
