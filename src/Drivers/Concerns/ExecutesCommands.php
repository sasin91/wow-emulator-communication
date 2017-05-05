<?php

namespace Sasin91\WoWEmulatorCommunication\Drivers\Concerns;

use Illuminate\Support\Arr;
use Sasin91\WoWEmulatorCommunication\Communication\CommunicationPipeline;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;

/**
 * Enables a CommunicationDriver to execute Commands.
 *
 * Class ExecutesCommands
 * @package Sasin91\WoWEmulatorCommunication\Drivers\Concerns
 */
trait ExecutesCommands
{
    use ResolvesCommunicationHandler;

    /**
     * Alias for fire.
     *
     * @param   EmulatorCommand|string  $command
     * @param   mixed                   $parameters
     * @return  mixed 	                Response from remote API.
     */
    public function command($command, $parameters = null)
    {
        return $this->fire($command, $parameters);
    }

    /**
     * Dispatch a command
     *
     * @param   EmulatorCommand|string  $command
* @param   mixed                   $parameters
     * @return  mixed 	                Response from remote API.
     */
    public function fire($command, $parameters = null)
    {
        return (new CommunicationPipeline)
            ->send($this->preparedCommand($command, $parameters))
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
            return $this->communicationHandler()->handle($command);
        };
    }
}