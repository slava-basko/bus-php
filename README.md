# Command Bus

For those who understand what it is and strive for simplicity.

Don't know what it is? Read this article (link to my blog).

Zero dependencies and PHP 5.5+.

```php
$bus = CommandBus::create([
    UserRegistrationCommand::class => new UserRegistrationCommandHandler(),
]);

$bus->handle(new UserRegistrationCommand('john.doe@example.com', 'secure-password'))
```

## Install

```bash
composer require slava-basko/bus-php
```