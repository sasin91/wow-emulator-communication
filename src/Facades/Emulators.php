<?php

namespace Sasin91\WoWEmulatorCommunication\Facades;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Str;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;
use Sasin91\WoWEmulatorCommunication\EmulatorManager;

class Emulators extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor()
    {
        return EmulatorManager::class;
    }

    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    /**
     * Alias for driver.
     * 
     * @param  string $driver
     * @return Sasin91\WoWEmulatorCommunication\Drivers\EmulatorCommunicationDriver
     */
    public static function emulator($driver)
    {
        return static::getFacadeRoot()->driver($driver);
    }

    /**
     * Dispatch a command to a emulator driver.
     * 
     * @param  string                   $emulator 
     * @param  EmulatorCommand|string   $command
     * @return mixed
     */
    public static function dispatchTo($emulator, $command)
    {
        return static::getFacadeRoot()->driver($emulator)->command($command);
    }

    /**
     * Handle dynamic, static calls to the object.
     *
     * @param  string  $method
     * @param  array   $args
     * @return mixed
     *
     * @throws \RuntimeException
     */
    public static function __callStatic($method, $args)
    {
        $manager = static::getFacadeRoot();

        if (config('emulator.proxy-driver-commands') 
            && $manager->hasDriver($driver = Str::lower($method))
        ) {
            return $manager->driver($driver)->command(new EmulatorCommand(...$args));
        }

        return $manager->$method(...$args);
    }
}