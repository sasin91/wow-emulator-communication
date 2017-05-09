<?php

namespace Sasin91\WoWEmulatorCommunication\Commands\Events;

/**
* Event fired when a Command is getting ready for take-off to the other side...
*
* default hook namespace: emulator.command.{$event}: {$name}
*/
class CommandFiring
{
	/**
	 * EmulatorCommand instance
	 * 
	 * @var \Sasin91\WoWEmulatorCommunication\EmulatorCommandContract
	 */
	public $command;

	/**
	 * CommandFiring event constructor.
	 * 
	 * @param \Sasin91\WoWEmulatorCommunication\EmulatorCommandContract $command
	 */
	public function __construct($command)
	{
		$this->command = $command;
	}
}