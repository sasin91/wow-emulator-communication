<?php

namespace Sasin91\WoWEmulatorCommunication;

use Illuminate\Support\Arr;
use Sasin91\WoWEmulatorCommunication\Commands\Concerns\ConcatenatesIntoCommandString;
use Sasin91\WoWEmulatorCommunication\Commands\Concerns\Dispatchable;
use Sasin91\WoWEmulatorCommunication\Concerns\UsesContainer;
use Sasin91\WoWEmulatorCommunication\EmulatorManager;

/**
* A Emulator EmulatorCommand object
*/
class EmulatorCommand implements EmulatorCommandContract
{
    use ConcatenatesIntoCommandString, Dispatchable, UsesContainer;

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
    public function __construct($command, $parameters = [], $delimiter = ' ')
    {
        $this->command = $command;
        $this->parameters = Arr::wrap($parameters);
        $this->delimiter = $delimiter;
    }

    /**
     * Dispatch a command to a specific driver.
     *
     * @param  string $driver
     * @param  string $command
     * @param  array  $parameters
     * @return mixed
     */
    public static function dispatchTo($driver, $command, $parameters = [])
    {
        return (new static($command, Arr::wrap($parameters)))->fire($driver);
    }

    /**
     * Get the command string.
     *
     * @return string
     */
    public function command()
    {
        return $this->command;
    }

    /**
     * Fire the command
     *
     * @param string|null $driver emulator driver name
     * @return mixed  Response from remote API.
     */
    public function fire($driver = null)
    {
        return $this->container()
        ->make(EmulatorManager::class)
        ->driver($driver)
        ->command($this);
    }
}
