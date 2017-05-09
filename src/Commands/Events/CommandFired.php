<?php

namespace Sasin91\WoWEmulatorCommunication\Commands\Events;

/**
* Event fired when a Command has taken off, in it's cute little space shuttle...
*
* default hook namespace: emulator.command.{$event}: {$name}
*/
class CommandFired
{
    /**
     * EmulatorCommand instance
     *
     * @var \Sasin91\WoWEmulatorCommunication\EmulatorCommandContract
     */
    public $command;

    /**
     * CommandFired event constructor.
     *
     * @param \Sasin91\WoWEmulatorCommunication\EmulatorCommandContract $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }
}
