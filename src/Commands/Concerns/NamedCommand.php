<?php 

namespace Sasin91\WoWEmulatorCommunication\Commands\Concerns;

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
        return $this->container()
        ->make(EmulatorManager::class)
        ->driver($this->driver())
        ->command($this);
    }
}
