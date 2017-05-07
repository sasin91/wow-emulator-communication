<?php

namespace Sasin91\WoWEmulatorCommunication;

use Illuminate\Support\Arr;
use Sasin91\WoWEmulatorCommunication\Commands\Concerns\ConcatenatesIntoCommandString;
use Sasin91\WoWEmulatorCommunication\Commands\Concerns\Dispatchable;

/**
* A Emulator EmulatorCommand object
*/
class EmulatorCommand
{
	use ConcatenatesIntoCommandString, Dispatchable;

	/**
	 * The command string.
	 * 
	 * @var string
	 */
	protected $command;

	/**
	 * EmulatorCommand Constructor
	 * 
	 * @param string $command    The command to fire through
	 * @param array  $parameters 
	 * @param string $delimiter  
	 */
	public function __construct(string $command, $parameters = [], $delimiter = ' ')
	{
		$this->command = $command;
		$this->parameters = Arr::wrap($parameters);
		$this->delimiter = $delimiter;
	}
}