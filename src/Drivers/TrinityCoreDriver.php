<?php

namespace Sasin91\WoWEmulatorCommunication\Drivers;

use Sasin91\WoWEmulatorCommunication\Communication\CommunicationHandler;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;
use Sasin91\WoWEmulatorCommunication\Communication\CommunicationPipeline;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\DispatchesDynamicCommands;
use Illuminate\Support\Arr;

/**
* TrinityCore driver.
*
* Fire calls through a pipeline to the communication handler.
*/
class TrinityCoreDriver
{
	use DispatchesDynamicCommands;
	
	/**
	 * The Remote API Communication handle.
	 *
	 * @var CommunicationHandler
	 */
	protected $communicationHandler;

	/**
	 * Array of Configurations
	 * 
	 * @var array
	 */
	protected $config;

	public function __construct($communicationHandler, array $config)
	{
		$this->config = $config;
		$this->communicationHandler = $communicationHandler;
	}

	/**
	 * Dispatch a command
	 * 
	 * @param  EmulatorCommand|string   $command
     * @param   mixed                   $parameters
	 * @return  mixed 	                Response from remote API.
	 */
	public function command($command, $parameters = null)
	{
	    $parameters = Arr::wrap($parameters);
		$command = $command instanceof EmulatorCommand ? $command->addParameters($parameters) : new EmulatorCommand($command, $parameters);

		return (new CommunicationPipeline)
		->send($command)
        ->via('handleTrinityCore')
		->through($this->config['pipes'])
		->then(function ($command) {
			return $this->communicationHandler->handle($command);
		});
	}
}