<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager\Tests\Cases\Handler;

use Tester;
use SixtyEightPublishers;

require __DIR__ . '/../../bootstrap.php';

final class WedosAccessHandlerTest extends Tester\TestCase
{
	/** @var \SixtyEightPublishers\RemoteAccessManager\Handler\WedosAccessHandler */
	private $handler;

	/**
	 * {@inheritdoc}
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->handler = new SixtyEightPublishers\RemoteAccessManager\Handler\WedosAccessHandler();
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
	 * Can't test method ::deny because method calls exit() function
	 */
	/*public function testDeny() : void
	{
	}*/
}

(new WedosAccessHandlerTest())->run();
