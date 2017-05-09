<?php

namespace Sasin91\WoWEmulatorCommunication\Commands\Events;

/**
* Event fired when a Command has been created.
*
* Provides a hook for when a Command has been instantiated but is not yet firing.
*
* default hook namespace: emulator.command.{$event}: {$name}
*/
class CommandCreated
{
    /**
     * EmulatorCommand instance
     *
     * @var \Sasin91\WoWEmulatorCommunication\EmulatorCommandContract
     */
    public $command;

    /**
     * CommandCreated event constructor.
     *
     * @param \Sasin91\WoWEmulatorCommunication\EmulatorCommandContract $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }
}
