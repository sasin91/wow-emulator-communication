<?php

namespace Sasin91\WoWEmulatorCommunication\Commands\Events;

/**
* Event fired when a Command is being created.
*
*
* default hook namespace: emulator.command.{$event}: {$name}
*
* A usecase for this could be to override rules tempoarily.
*/
class CommandCreating
{
    /**
     * EmulatorCommand instance
     *
     * @var \Sasin91\WoWEmulatorCommunication\EmulatorCommandContract
     */
    public $command;

    /**
     * CommandCreating event constructor.
     *
     * @param \Sasin91\WoWEmulatorCommunication\EmulatorCommandContract $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }
}
