<?php

declare(strict_types=1);

namespace SixtyEightPublishers\RemoteAccessManager;

use Nette;
use SixtyEightPublishers;

final class RemoteAccessManager implements IRemoteAccessManager
{
	use Nette\SmartObject;

	/** @var \Nette\Http\IRequest */
	private $request;

	/** @var \SixtyEightPublishers\RemoteAccessManager\Handler\IAccessHandler */
	private $handler;

	/** @var array */
	private $whitelist;

	/** @var array */
	private $blacklist;

	/** @var string */
	private $key;

	/** @var int */
	private $mode;

	/** @var bool */
	private $consoleMode;

	/**
	 * @param \Nette\Http\IRequest                                             $request
	 * @param \SixtyEightPublishers\RemoteAccessManager\Handler\IAccessHandler $handler
	 * @param array                                                            $blacklist
	 * @param array                                                            $whitelist
	 * @param bool                                                             $mode
	 * @param string                                                           $key
	 * @param bool                                                             $consoleMode
	 */
	public function __construct(
		Nette\Http\IRequest $request,
		Handler\IAccessHandler $handler,
		array $whitelist = [],
		array $blacklist = [],
		bool $mode = self::ALLOW_ALL,
		string $key = self::COOKIE_SECRET,
		bool $consoleMode = FALSE
	) {
		$this->request = $request;
		$this->handler = $handler;
		$this->whitelist = $whitelist;
		$this->blacklist = $blacklist;
		$this->mode = $mode;
		$this->key = $key;
		$this->consoleMode = $consoleMode;
	}

	/**
	 * @return bool
	 */
	private function isAllowed(): bool
	{
		if ($this->consoleMode) {
			return TRUE;
		}

		$addr = $this->request->getRemoteAddress() ?: php_uname('n');
		$secret = !empty($this->key) ? $this->request->getCookie($this->key) : '';

		if ($this->mode === self::ALLOW_ALL) {
			$allow = !(in_array($addr, $this->blacklist, TRUE) || (!empty($secret) && in_array("$secret@$addr", $this->blacklist, TRUE)));
		} else {
			$whitelist = $this->whitelist;

			if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$whitelist[] = '127.0.0.1';
				$whitelist[] = '::1';
			}

			$allow = in_array($addr, $whitelist, TRUE) || (!empty($secret) && (in_array("$secret@$addr", $whitelist, TRUE) || in_array($secret, $whitelist, TRUE)));
		}

		return $allow;
	}

	/******************* interface \SixtyEightPublishers\RemoteAccessManager\IRemoteAccessManager *******************/

	/**
	 * {@inheritdoc}
	 */
	public function process(): void
	{
		$this->isAllowed()
			? $this->handler->allow()
			: $this->handler->deny();
	}
}
