<?php 

namespace Sasin91\WoWEmulatorCommunication\Commands\Concerns;

use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandFired;
use Sasin91\WoWEmulatorCommunication\Commands\Events\CommandFiring;
use Sasin91\WoWEmulatorCommunication\Concerns\UsesContainer;
use Sasin91\WoWEmulatorCommunication\EmulatorManager;

trait NamedCommand
{
    use UsesContainer, ConcatenatesIntoCommandString;

    /**
     * Fire the command
     *
     * @return mixed  Response from remote API.
     */
    public function fire()
    {
        $this->fireCommandEvent(new CommandFiring($this));

        $response = $this->container()
        ->make(EmulatorManager::class)
        ->driver($this->driver())
        ->command($this);

        $this->fireCommandEvent(new CommandFired($this));

        return $response;
    }
}
