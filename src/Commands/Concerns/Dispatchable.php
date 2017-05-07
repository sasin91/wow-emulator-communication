<?php

namespace Sasin91\WoWEmulatorCommunication\Commands\Concerns;

use Illuminate\Support\Arr;

trait Dispatchable 
{
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
}