# Remote Access

Simply block or allow remote access in Nette applications.

## Installation

The best way to install 68publishers/remote-access is using Composer:

```bash
composer require 68publishers/remote-access
```

then you can register extension into DIC:

```yaml
extensions:
    remote_access: SixtyEightPublishers\Application\RemoteAccessManager\DI\RemoteAccessManagerExtension
```

## Configuration

```yaml
remote_access:
    enabled: yes # default
    allow_all: no # default is `yes`
    
    # if you want to compare specific cookie's value, default is `ram-secret-key`. If you want to disable this you can set empty string ''
    secret_key: 'my-cookie'
    
    # whitelist is used when `allow_all` is `no`
    whitelist:
        - 192.0.0.12
        - foo@192.0.0.13 # if `secret_key` is set
    
    # blacklist is used when `allow_all` is `yes`
    blacklist:
    	- 192.0.0.14
    	- bar@192.0.0.15
    
    # if you want to change default access handler
    handler: SixtyEightPublishers\RemoteAccessManager\Handler\WedosAccessHandler
```

## Contributing

Before committing any changes, don't forget to run

```bash
vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run
```

and

```bash
vendor/bin/tester ./tests
```
