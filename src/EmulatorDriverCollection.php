<?php

namespace Sasin91\WoWEmulatorCommunication;

use Illuminate\Support\Collection;

class EmulatorDriverCollection extends Collection
{
	public function __call($method, $parameters = [])
	{
		if (static::hasMacro($method)) {
			return parent::__call($method, $parameters);
		}

		return $this->map->$method(...$parameters);
	}
}