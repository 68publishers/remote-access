<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager\Tests\Cases\DI;

use Nette;
use Tester;
use SixtyEightPublishers;

require __DIR__ . '/../../bootstrap.php';

class RemoteAccessExtensionIntegrationTest extends Tester\TestCase
{
	/**
	 * @return void
	 */
	public function testRemoteAccessManagerRegistered(): void
	{
		$container = SixtyEightPublishers\RemoteAccessManager\Tests\Helper\ContainerFactory::createContainer(
			static::class . __METHOD__,
			[
				'remote_access' => [
					'enabled' => TRUE,
				],
			]
		);

		Tester\Assert::type(
			SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager::class,
			$container->getByType(SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager::class)
		);
	}

	/**
	 * @return void
	 */
	public function testCustomHandlerRegistered(): void
	{
		$container = SixtyEightPublishers\RemoteAccessManager\Tests\Helper\ContainerFactory::createContainer(
			static::class . __METHOD__,
			[
				'remote_access' => [
					'enabled' => TRUE,
					'handler' => SixtyEightPublishers\RemoteAccessManager\Tests\Fixture\ThrowExceptionAccessHandler::class,
				],
			]
		);

		Tester\Assert::type(
			SixtyEightPublishers\RemoteAccessManager\Tests\Fixture\ThrowExceptionAccessHandler::class,
			$container->getService('remote_access.handler')
		);
	}

	/**
	 * @return void
	 */
	public function testDisabledExtension(): void
	{
		$container = SixtyEightPublishers\RemoteAccessManager\Tests\Helper\ContainerFactory::createContainer(
			static::class . __METHOD__,
			[
				'remote_access' => [
					'enabled' => FALSE,
				],
			]
		);

		Tester\Assert::exception(function () use ($container) {
			$container->getByType(SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager::class);
		}, Nette\DI\MissingServiceException::class);
	}
}

(new RemoteAccessExtensionIntegrationTest())->run();
