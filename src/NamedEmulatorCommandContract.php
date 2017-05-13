<?php

namespace Sasin91\WoWEmulatorCommunication;

/**
 * Named Emulator Command Contract.
 *
 * In most cases the fire() method should be implemented,
 * by using the `Sasin91\WoWEmulatorCommunication\Commands\Concerns\NamedCommand` trait.
 */
interface NamedEmulatorCommandContract extends EmulatorCommandContract
{
    /**
     * Get the name of the underlying driver
     * or null for default.
     *
     * @return string|null
     */
    public function driver();

        /**
     * Dispatch the command.
     *
     * @param  dynamic
     * @return mixed [Response from remote API.]
     */
    public static function dispatch();

    /**
     * Dispatch the command.
     *
     * @return mixed [Response  from remote API.]
     */
    public function fire();
}
