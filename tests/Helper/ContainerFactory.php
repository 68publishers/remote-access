<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager\Tests\Helper;

use Nette;
use Tester;
use SixtyEightPublishers;

final class ContainerFactory
{
	/**
	 * @param string       $name
	 * @param string|array $config
	 *
	 * @return \Nette\DI\Container
	 */
	public static function createContainer(string $name, $config): Nette\DI\Container
	{
		if (!defined('TEMP_PATH')) {
			define('TEMP_PATH', __DIR__ . '/../temp');
		}

		$loader = new Nette\DI\ContainerLoader(TEMP_PATH . '/cache/Nette.Configurator', TRUE);
		$class = $loader->load(static function (Nette\DI\Compiler $compiler) use ($config): void {
			$compiler->addExtension('remote_access', new SixtyEightPublishers\Application\RemoteAccessManager\DI\RemoteAccessManagerExtension());

			if (is_array($config)) {
				$compiler->addConfig($config);
			} elseif (is_file($config)) {
				$compiler->loadConfig($config);
			} else {
				$compiler->loadConfig(Tester\FileMock::create((string) $config, 'neon'));
			}
		}, $name);

		return new $class();
	}
}
