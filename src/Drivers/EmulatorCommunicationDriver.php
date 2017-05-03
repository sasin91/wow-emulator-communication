<?php

namespace Sasin91\WoWEmulatorCommunication\Drivers;

use Illuminate\Support\Arr;
use Sasin91\WoWEmulatorCommunication\Communication\CommunicationHandler;
use Sasin91\WoWEmulatorCommunication\Communication\CommunicationPipeline;
use Sasin91\WoWEmulatorCommunication\Communication\SoapCommunicator;
use Sasin91\WoWEmulatorCommunication\Drivers\Concerns\DispatchesDynamicCommands;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;

/**
* Emulator Communication Driver driver.
*
* Fire calls through a pipeline to the communication handler.
*/
class EmulatorCommunicationDriver
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

	/**
	 * Name of the driver.
	 * @var string
	 */
	protected $name;

	public function __construct($name, array $config)
	{
		$this->name = $name;
		$this->config = $config;

		$this->setup();
	}

	protected function setup()
	{
		$this->communicationHandler = $this->resolveCommunicationHandler();

		$name = "Emulator.Communication.Handler.{$this->name}";
		if ($this->communicationHandler instanceof SoapCommunicator) {
			$this->configureSoap($name);
		} else {
			$this->configureSocket($name);
		}
	}

	protected function resolveCommunicationHandler()
	{
		if (app()->bound($this->config['handler']) || class_exists($this->config['handler'])) {
			return app()->make($this->config['handler']);
		}

		$handler = 'Emulator.Communication.Communicators.'.$this->config['handler'];
		return app($handler);
	}

	protected function configureSoap($name)
	{
		$options = array_merge(
			Arr::get($this->config, 'credentials', []),
			config("emulator.servers.{$this->name}.soap", [])
		);

		$this->communicationHandler->configure($name, $options);
	}

	protected function configureSocket($name)
	{
		$options = array_merge(
			Arr::get($this->config, 'credentials', []),
			config("emulator.servers.{$this->name}.ra", [])
		);

		$this->communicationHandler->configure($name, $options);
	}

	/**
	 * Dispatch a command
	 * 
	 * @param   EmulatorCommand|string  $command
     * @param   mixed                   $parameters
	 * @return  mixed 	                Response from remote API.
	 */
	public function command($command, $parameters = null)
	{
		return (new CommunicationPipeline)
		    ->send($this->preparedCommand($command, Arr::wrap($parameters)))
		    ->through(Arr::get($this->config, 'pipes', []))
		    ->then($this->executeCommand());
	}
	
	/**
	 * Prepare a EmulatorCommand.
	 * 
	 * @param  string|EmulatorCommand $command
	 * @param  mixed $parameters
	 * @return EmulatorCommand
	 */
	protected function preparedCommand($command, $parameters)
	{
		$parameters = Arr::wrap($parameters);

		return $command instanceof EmulatorCommand 
			? $command->addParameters($parameters) 
			: new EmulatorCommand($command, $parameters);
	}

	/**
	 * Execute the EmulatorCommand.
	 * 
	 * @return \Closure
	 */
	protected function executeCommand()
	{
		return function ($command) {
			return $this->communicationHandler->handle($command);
		};
	}
}