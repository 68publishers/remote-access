<?php

declare(strict_types=1);

namespace SixtyEightPublishers\Application\RemoteAccessManager\DI;

use Nette;
use SixtyEightPublishers;

class RemoteAccessManagerExtension extends Nette\DI\CompilerExtension
{
	/** @var array */
	private $defaults = [
		'enabled' => TRUE,
		'allow_all' => SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager::ALLOW_ALL,
		'secret_key' => SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager::COOKIE_SECRET,
		'whitelist' => [],
		'blacklist' => [],
		'handler' => SixtyEightPublishers\RemoteAccessManager\Handler\DefaultAccessHandler::class,
	];

	/**
	 * @throws Nette\Utils\AssertionException
	 */
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults);

		Nette\Utils\Validators::assertField($config, 'enabled', 'bool');

		if (FALSE === $config['enabled']) {
			return;
		}

		Nette\Utils\Validators::assertField($config, 'allow_all', 'bool');
		Nette\Utils\Validators::assertField($config, 'secret_key', 'string');
		Nette\Utils\Validators::assertField($config, 'whitelist', 'string|array');
		Nette\Utils\Validators::assertField($config, 'blacklist', 'string|array');
		Nette\Utils\Validators::assertField($config, 'handler', 'string|' . Nette\DI\Statement::class);

		if (!is_string($config['handler']) || !Nette\Utils\Strings::startsWith($config['handler'], '@')) {
			$config['handler'] = $builder->addDefinition($this->prefix('handler'))
				->setType(SixtyEightPublishers\RemoteAccessManager\Handler\IAccessHandler::class)
				->setFactory($config['handler'])
				->setAutowired(FALSE);
		}

		$builder->addDefinition($this->prefix('remote_access_manager'))
			->setType(SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager::class)
			->setType(SixtyEightPublishers\RemoteAccessManager\RemoteAccessManager::class)
			->setArguments([
				'handler' => $config['handler'],
				'whitelist' => is_string($config['whitelist']) ? preg_split('#[,\s]+#', $config['whitelist']) : $config['whitelist'],
				'blacklist' => is_string($config['blacklist']) ? preg_split('#[,\s]+#', $config['blacklist']) : $config['blacklist'],
				'mode' => $config['allow_all'],
				'key' => $config['secret_key'],
				'consoleMode' => $builder->parameters['consoleMode'],
			])
			->addTag('run', TRUE)
			->addSetup('process');
	}
}
