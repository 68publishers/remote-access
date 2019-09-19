<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager\Handler;

use Nette;

final class DefaultAccessHandler implements IAccessHandler
{
	use Nette\SmartObject;

	/** @var string */
	private $message = 'Access denied!';

	/**
	 * @param string $message
	 *
	 * @return void
	 */
	public function setDenyMessage(string $message): void
	{
		$this->message = $message;
	}

	/******************* interface \SixtyEightPublishers\RemoteAccessManager\Handler\IAccessHandler *******************/

	/**
	 * {@inheritdoc}
	 */
	public function allow(): void
	{
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Nette\Application\ForbiddenRequestException
	 */
	public function deny(): void
	{
		throw new Nette\Application\ForbiddenRequestException($this->message);
	}
}
