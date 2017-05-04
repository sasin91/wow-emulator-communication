<?php

namespace Sasin91\WoWEmulatorCommunication;

use Illuminate\Support\Collection;

/**
 * Emulator Driver Collection
 *
 * Provides the usual Collection with all it packs
 * in addition to proxy mapping dynamic calls to each Driver.
 */
class EmulatorDriverCollection extends Collection
{
	/**
	 * Dynamically map and proxy calls to the Driver(s).
	 * 
	 * @param  string $method
	 * @param  array  $parameters
	 * @return $this
	 */
	public function __call($method, $parameters = [])
	{
		if (static::hasMacro($method)) {
			return parent::__call($method, $parameters);
		}

		return $this->map->$method(...$parameters);
	}
}