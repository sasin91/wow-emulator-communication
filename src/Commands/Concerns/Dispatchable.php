<?php

namespace Sasin91\WoWEmulatorCommunication\Commands\Concerns;

use Sasin91\WoWEmulatorCommunication\Concerns\UsesContainer;
use Sasin91\WoWEmulatorCommunication\EmulatorManager;

trait Dispatchable 
{
	use UsesContainer;

	/**
	 * Dispatch a command to the default driver.
	 * 
	 * @param  string $command    
	 * @param  array  $parameters 
	 * @return mixed
	 */
	public static function dispatch($command, $parameters = [])
	{
		return (new static($command, Arr::wrap($parameters)))->fire();
	}

	/**
	 * Dispatch a command to a specific driver.
	 * 
	 * @param  string $driver
	 * @param  string $command 
	 * @param  array  $parameters
	 * @return mixed
	 */
	public static function dispatchTo($driver, $command, $parameters = [])
	{
		return (new static($command, Arr::wrap($parameters)))->fire($driver);
	}

	/**
	 * Fire the command
	 *
	 * @param string|null $driver emulator driver name
	 * @return mixed  Response from remote API.
	 */
	public function fire($driver = null)
	{
		return $this->container()
		->make(EmulatorManager::class)
		->driver($driver)
		->command($this);
	}
}