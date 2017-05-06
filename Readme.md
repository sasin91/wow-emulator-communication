# World of Warcraft Emulator Communication (Laravel 5 Package)

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

Remote API Communication package for WoW private servers

## Contents

- [Installation](#installation)
- [Configuration](#configuration)
    - [Custom drivers](#custom-drivers)
    	- [Driver traits](#driver-concerns)
    - [Communication pipes](#communication-pipes)
- [Usage](#usage)
    - [Facade](#facade)
    - [Manager](#driver-manager)
- [Testing](#testing)
- [Events](#events)
- [License](#license)

<a name="installation" />

## Installation

## For Laravel ~5

    composer require sasin91/wow-emulator-communication



Add the following service provider in your `providers` array, in your `config/app.php`

    \Sasin91\WoWEmulatorCommunication\EmulatorServiceProvider::class,

<a name="configuration"/>

## Configuration

To publish `emulator.php` config file, run the following, `vendor:publish` command.

```bash
php artisan vendor:publish --provider="\Sasin91\WoWEmulatorCommunication\EmulatorServiceProvider"
```

You may configure the config file to your liking, however the defaults should work for most cases.

If you would like to dispatch a command to multiple drivers, prefix a driver with `multiple` that returns an array of existing drivers.

<a name="custom-drivers" />

### Custom drivers

In addition to the config file, it is also possible to register your own drivers directly on the Manager, by the following syntax:

```php
\Emulators::extend(string $class_name, \Closure $callback);
```
The $callback should return an implementation of `Sasin91\WoWEmulatorCommunication\Drivers\EmulatorCommunicationContract`.

In addition to providing your own implementation, you're also welcome to use the generic driver, like so:
```php
\Emulators::extend('SkyFire', \Emulators::genericDriverCallback());
```

### Note: driver constructors should accept a string $name and array of $configurations as parameters.
** There are no constructor dependency injection support implemented. **

<a name="driver-concerns" />

### Custom driver trait

For your own implementation, there is some convenient traits available in the `Sasin91\WoWEmulatorCommunication\Drivers\Concerns` namespace:

	* DispatchesDynamicCommands [allows for commands such as Emulator::accountOnlineList() or Emulator::account_online_list()]
	* ExecutesCommands [Enables execution of Command(s)]
	* HasConfigurations

Only ExecutesCommands is mandatory for the most parts.
However without the HasConfigurations trait, the Contract would require you to implement your own `config($key = null, $default = null)` method.

Optionally, you can extend the default driver, `Sasin91\WoWEmulatorCommunication\Drivers\EmulatorCommunicationDriver` instead.

<a name="communication-pipes" />

#### Communication Pipes

Think of the pipes as a middleware layer that runs before a command is passed to the Handler.

The pipes receives the are plain PHP classes that should implement a public handle method, 
which receives Command object and a Closure representing the next slice of the stack.

```
public function handle($command, $next) 
{
	// Your Logic

	return $next($command);
}
```

if you need inspiration, take a look at the existing Pipes in the `Sasin91\WoWEmulatorCommunication\Communication\Pipes` namespace.

For custom drivers, you should register pipes like any other driver, in the 'drivers' array in the `emulator.php` config file.


As with any package, it's a good idea to refresh composer autoloader.
```bash
composer dump-autoload
```

<a name="middleware">

### Http Middleware

# TODO

**And you are ready to go.**

<a name="usage" />

## Usage

<a name="facade" />

### Emulators Facade

#### Dispatching a command to the default driver
```php
\Emulators::command($command);
```

#### Dispatching a command a specific driver
```php
\Emulators::driver('driver')->command($command);
```

#### Dispatching a command to multiple drivers
```php
\Emulators::driver('multiple')->command($command);
```

In addition to the conventional driver method, it is also possible to call
```php
\Emulators::emulator('driver')
```
instead.
As an extra convenience, `dispatchTo($driver, $command)` is also available on the Facade.

$command can be an instance of `\Sasin91\WoWEmulatorCommunication\EmulatorCommand` or a string.

<a name="driver-manager" />

### Driver Manager

There isn't really that much to say about this, if you prefer depedency injection over service locator (ie. Facade), then injecting or resolving the `\Sasin91\WoWEmulatorCommunication\EmulatorManager` is also an option.

<a name="testing" />

## Testing
# TODO

<a name="events" />

## Events
# TODO

<a name="license" />

## License

wow-emulator-communication is free software distributed under the terms of the MIT license.
