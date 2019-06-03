<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager\Tests\Fixture;

use SixtyEightPublishers;

final class ThrowExceptionAccessHandler implements SixtyEightPublishers\RemoteAccessManager\Handler\IAccessHandler
{
	/******************* interface \SixtyEightPublishers\RemoteAccessManager\Handler\IAccessHandler *******************/

	/**
	 * {@inheritdoc}
	 */
	public function allow(): void
	{
		throw new \RuntimeException('ALLOWED');
	}
	/**
	 * {@inheritdoc}
	 */
	public function deny(): void
	{
		throw new \RuntimeException('DENIED');
	}
}
