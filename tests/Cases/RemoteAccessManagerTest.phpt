<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager\Tests\Cases;

use Nette;
use Tester;
use Mockery;
use SixtyEightPublishers;

require __DIR__ . '/../bootstrap.php';

final class RemoteAccessManagerTest extends Tester\TestCase
{
	/** @var bool */
	public const ALLOW_ALL = SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager::ALLOW_ALL;

	/** @var bool */
	public const DENY_ALL = SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager::DENY_ALL;

	/**
	 * {@inheritdoc}
	 */
	protected function tearDown(): void
	{
		parent::tearDown();

		Mockery::close();
	}

	/**
	 * @return void
	 */
	public function testAllowInConsoleMode(): void
	{
		$this->assertAllow($this->createManager([], [], self::ALLOW_ALL, '', TRUE));
	}

	/**
	 * @return void
	 */
	public function testAllowAllMode(): void
	{
		$this->assertAllow($this->createManager([], [], self::ALLOW_ALL, '', FALSE));
	}

	/**
	 * @return void
	 */
	public function testDenyInAllowAllModeWithBlacklist(): void
	{
		$this->assertDeny($this->createManager([], [
			'1.2.3.4',
		], self::ALLOW_ALL, '', FALSE));
	}

	/**
	 * @return void
	 */
	public function testDenyInAllowAllModeWithBlacklistAndSecretKey(): void
	{
		$this->assertDeny($this->createManager([], [
			'abcd@1.2.3.4',
		], self::ALLOW_ALL, 'secret_key', FALSE));
	}

	/**
	 * @return void
	 */
	public function testDenyAllMode(): void
	{
		$this->assertDeny($this->createManager([], [], self::DENY_ALL, '', FALSE));
	}

	/**
	 * @return void
	 */
	public function testAllowInDenyAllModeWithWhitelist(): void
	{
		$this->assertAllow($this->createManager([
			'1.2.3.4',
		], [], self::DENY_ALL, '', FALSE));
	}

	/**
	 * @return void
	 */
	public function testAllowInDenyAllModeWithWhitelistAndSecretKey(): void
	{
		$this->assertAllow($this->createManager([
			'abcd@1.2.3.4',
		], [], self::DENY_ALL, 'secret_key', FALSE));
	}

	/**
	 * @param array  $whitelist
	 * @param array  $blacklist
	 * @param bool   $mode
	 * @param string $key
	 * @param bool   $consoleMode
	 *
	 * @return \SixtyEightPublishers\RemoteAccessManager\RemoteAccessManager
	 */
	private function createManager(
		array $whitelist,
		array $blacklist,
		bool $mode,
		string $key,
		bool $consoleMode = FALSE
	): SixtyEightPublishers\RemoteAccessManager\RemoteAccessManager {
		$request = Mockery::mock(Nette\Http\IRequest::class);

		$request->shouldReceive('getRemoteAddress')->andReturn('1.2.3.4');
		$request->shouldReceive('getCookie')->with($key)->andReturn('abcd');

		return new SixtyEightPublishers\RemoteAccessManager\RemoteAccessManager(
			$request,
			new SixtyEightPublishers\RemoteAccessManager\Tests\Fixture\ThrowExceptionAccessHandler(),
			$whitelist,
			$blacklist,
			$mode,
			$key,
			$consoleMode
		);
	}

	/**
	 * @param \SixtyEightPublishers\RemoteAccessManager\RemoteAccessManager $manager
	 */
	private function assertAllow(SixtyEightPublishers\RemoteAccessManager\RemoteAccessManager $manager): void
	{
		Tester\Assert::exception(
			static function () use ($manager) {
				$manager->process();
			},
			\RuntimeException::class,
			'ALLOWED'
		);
	}

	/**
	 * @param \SixtyEightPublishers\RemoteAccessManager\RemoteAccessManager $manager
	 */
	private function assertDeny(SixtyEightPublishers\RemoteAccessManager\RemoteAccessManager $manager): void
	{
		Tester\Assert::exception(
			static function () use ($manager) {
				$manager->process();
			},
			\RuntimeException::class,
			'DENIED'
		);
	}
}

(new RemoteAccessManagerTest())->run();
