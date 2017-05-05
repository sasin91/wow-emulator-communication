<?php

namespace Sasin91\WoWEmulatorCommunication\Drivers;

use Sasin91\WoWEmulatorCommunication\EmulatorCommand;


/**
 * Emulator Communication Driver driver.
 *
 * Fire calls through a pipeline to the communication handler.
 */
interface EmulatorCommunicationContract
{
    /**
     * Alias for fire.
     *
     * @param   EmulatorCommand|string $command
     * @param   mixed $parameters
     * @return  mixed                    Response from remote API.
     */
    public function command($command, $parameters = null);

    /**
     * Dispatch a command
     *
     * @param   EmulatorCommand|string $command
     * @param   mixed $parameters
     * @return  mixed                    Response from remote API.
     */
    public function fire($command, $parameters = null);

    /**
     * Return a configuration or the current one.
     *
     * @param null|string   $key
     * @param mixed         $default
     * @return array
     */
    public function config($key = null, $default = null);
}