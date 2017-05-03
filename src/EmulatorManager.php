<?php 

namespace Sasin91\WoWEmulatorCommunication;

use Artisaninweb\SoapWrapper\SoapWrapper;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use Sasin91\WoWEmulatorCommunication\Communication\SoapCommunicator;
use Sasin91\WoWEmulatorCommunication\Drivers\EmulatorCommunicationDriver;

/**
* Emulator Driver manager
*/
class EmulatorManager extends Manager
{
        /**
     * Get a driver instance.
     *
     * @param  string  $driver
     * @return mixed
     */
    public function driver($driver = null)
    {
        if (Str::startsWith($driver,'multiple')) {
            return $this->createMultipleDriver($driver);
        }

        return parent::driver($driver);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
    	return $this->app->config->get('emulator.default');
    }

    /**
     * Create multiple drivers and return a collection which proxy maps dynamic calls to them.
     * 
     * @param  string $driver
     * @return EmulatorDriverCollection
     */
    public function createMultipleDriver($driver = null)
    {
        return EmulatorDriverCollection::make(
            $this->app->config->get("emulator.drivers.{$driver}")
        )->flatten()->filter(function ($driver) {
            return $this->hasDriver($driver);
        })->map(function ($driver) {
            return $this->createDriver($driver);
        });
    }

    /**
     * Checks whether the Manager has a given driver.
     * 
     * @param  string  $driver
     * @return boolean       
     */
    public function hasDriver($driver)
    {
        return isset($this->customCreators[$driver]) 
        || method_exists($this, 'create'.Str::studly($driver).'Driver');
    }

    /**
     * A generic Emulator communication driver,
     * this is the default driver unless otherwise specified.
     * 
     * @param  string $emulator
     * @return EmulatorCommunicationDriver
     */
    public function useGenericDriverFor($emulator)
    {
        $config = $this->app->config->get("emulator.drivers.{$emulator}");
    
        return new EmulatorCommunicationDriver($emulator, $config);
    }

    /**
     * Get a callback for wrapping a dynamic driver
     * 
     * @param  string $driver
     * @return \Closure
     */
    public function genericDriverCallback($driver)
    {
        return function () use($driver) {
            return $this->useGenericDriverFor($driver);
        };
    }
}