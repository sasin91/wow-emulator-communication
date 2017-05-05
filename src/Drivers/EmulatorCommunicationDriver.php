<?php

namespace Sasin91\WoWEmulatorCommunication\Drivers;

use Illuminate\Support\Arr;
use Sasin91\WoWEmulatorCommunication\Communication\SoapHandler;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\DispatchesDynamicCommands;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\ExecutesCommands;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\HasConfigurations;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\ResolvesCommunicationHandler;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\UsesContainer;

/**
* Emulator Communication Driver driver.
*
* Fire calls through a pipeline to the communication handler.
*/
class EmulatorCommunicationDriver implements EmulatorCommunicationContract
{
	use HasConfigurations, DispatchesDynamicCommands, ExecutesCommands;

    /**
	 * Name of the driver.
	 * @var string
	 */
	protected $name;

	/**
	 * Emulator Communication Driver Constructor
	 * 
	 * @param string $name   [Name of the Emulator driver]
	 * @param array  $config 
	 */
	public function __construct($name, array $config)
	{
		$this->name = $name;
		$this->config = $config;

		$this->bootTraits();
	}

    /**
     * Boot the traits.
     *
     * shamelessly copied from \Illuminate\Database\Eloquent\Model@bootTraits.
     * @credits <original author>
     *
     * @return void
     */
    protected function bootTraits()
    {
        $class = static::class;

        foreach (class_uses_recursive($class) as $trait) {
            if (method_exists($class, $method = 'boot'.class_basename($trait))) {
                // call_user_func([$this, $method]);
                $this->$method();
            }
        }
    }
}