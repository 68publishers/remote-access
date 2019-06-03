<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager\Tests\Cases\Handler;

use Nette;
use Tester;
use SixtyEightPublishers;

require __DIR__ . '/../../bootstrap.php';

final class DefaultAccessHandlerTest extends Tester\TestCase
{
	/** @var \SixtyEightPublishers\RemoteAccessManager\Handler\DefaultAccessHandler */
	private $handler;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->handler = new SixtyEightPublishers\RemoteAccessManager\Handler\DefaultAccessHandler();
	}

	/**
	 * @return void
	 */
	public function testAllow(): void
	{
		Tester\Assert::noError(function () {
			$this->handler->allow();
		});
	}

	/**
	 * @return void
	 */
	public function testDeny(): void
	{
		Tester\Assert::exception(
			function () {
				$this->handler->deny();
			},
			Nette\Application\BadRequestException::class,
			'Access denied!',
			Nette\Http\IResponse::S403_FORBIDDEN
		);
	}

	/**
	 * @return void
	 */
	public function testDenyWithCustomMessage(): void
	{
		$handler = new SixtyEightPublishers\RemoteAccessManager\Handler\DefaultAccessHandler();
		$handler->setDenyMessage('My custom message!');

		Tester\Assert::exception(
			function () use ($handler) {
				$handler->deny();
			},
			Nette\Application\BadRequestException::class,
			'My custom message!',
			Nette\Http\IResponse::S403_FORBIDDEN
		);
	}
}

(new DefaultAccessHandlerTest())->run();
