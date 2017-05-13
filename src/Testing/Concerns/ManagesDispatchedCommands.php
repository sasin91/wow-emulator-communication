<?php

namespace Sasin91\WoWEmulatorCommunication\Testing\Concerns;

use PHPUnit\Framework\Assert as PHPUnit;
use Sasin91\WoWEmulatorCommunication\EmulatorCommandContract;
use Sasin91\WoWEmulatorCommunication\NamedEmulatorCommandContract;

trait ManagesDispatchedCommands
{
	/**
     * The dispatched commands.
     * 
     * @var array
     */
    protected $commands = [];

    /**
     * Add a dispatched command.
     * 
     * @param EmulatorCommandContract|NamedEmulatorCommandContract $command
     */
    public function addDispatchedCommand(EmulatorCommandContract $command)
    {
        $this->commands[get_class($command)][] = $command->parameters();
    }

    /**
     * Get all the dispatched commands
     * 
     * @return array
     */
    public function getDispatchedCommands()
    {
        return $this->commands;
    }

    /**
     * Assert if an command was dispatched based on a truth-test callback.
     *
     * @param  string  $command
     * @param  callable|null  $callback
     * @return void
     */
    public function assertDispatched($command, $callback = null)
    {
        PHPUnit::assertTrue(
            $this->dispatched($command, $callback)->count() > 0,
            "The expected [{$command}] command was not dispatched."
        );
    }

    /**
     * Determine if an command was dispatched based on a truth-test callback.
     *
     * @param  string  $command
     * @param  callable|null  $callback
     * @return void
     */
    public function assertNotDispatched($command, $callback = null)
    {
        PHPUnit::assertTrue(
            $this->dispatched($command, $callback)->count() === 0,
            "The unexpected [{$command}] command was dispatched."
        );
    }

    /**
     * Get all of the commands matching a truth-test callback.
     *
     * @param  string  $command
     * @param  callable|null  $callback
     * @return \Illuminate\Support\Collection
     */
    public function dispatched($command, $callback = null)
    {
        if (! $this->hasDispatched($command)) {
            return collect();
        }

        $callback = $callback ?: function () {
            return true;
        };

        return collect($this->commands[$command])->filter(function ($arguments) use ($callback) {
            return $callback(...$arguments);
        });
    }

    /**
     * Determine if the given command has been dispatched.
     *
     * @param  string  $command
     * @return bool
     */
    public function hasDispatched($command)
    {
        return isset($this->commands[$command]) 
        && ! empty($this->commands[$command]);
    }
}