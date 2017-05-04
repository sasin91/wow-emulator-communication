<?php

namespace Sasin91\WoWEmulatorCommunication;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Sasin91\WoWEmulatorCommunication\Facades\Emulators;

/**
* A Emulator EmulatorCommand object
*/
class EmulatorCommand
{
	/**
	 * The command string.
	 * 
	 * @var string
	 */
	protected $command;

	/**
	 * The command parameters.
	 * 
	 * @var array
	 */
	protected $parameters = [];

	/**
	 * The parameter delimiter
	 * 
	 * @var string
	 */
	protected $delimiter = ' ';

	/**
	 * EmulatorCommand Constructor
	 * 
	 * @param string $command    The command to fire through
	 * @param array  $parameters 
	 * @param string $delimiter  
	 */
	public function __construct(string $command, $parameters = [], $delimiter = ' ')
	{
		$this->command = $command;
		$this->parameters = Arr::wrap($parameters);
		$this->delimiter = $delimiter;
	}

	/**
	 * Get the command represented as a string.
	 * 
	 * @return string 
	 */
	public function __toString()
	{
		$parameters = $this->formatParameters();
		return rtrim("{$this->command} {$parameters}");
	}

	/**
	 * Dispatch a command to the default driver.
	 * 
	 * @param  string $command    
	 * @param  array  $parameters 
	 * @return mixed
	 */
	public static function dispatch($command, $parameters = [])
	{
		return (new static($command, Arr::wrap($parameters)))->fire();
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
	 * Fire the command
	 *
	 * @param string|null $driver emulator driver name
	 * @return mixed  Response from remote API.
	 */
	public function fire($driver = null)
	{
		return Emulators::driver($driver)->command($this);
	}

	/**
	 * Use given delimiter
	 * 
	 * @param  string $delimiter 
	 * @return $this
	 */
	public function useDelimiter($delimiter)
	{
		$this->delimiter = $delimiter;

		return $this;
	}

	/**
	 * Add an array of parameters to the command query.
	 * 
	 * @param array $parameters
	 */
    public function addParameters(array $parameters)
    {
        $this->parameters = array_merge($this->parameters, $parameters);

        return $this;
	}

	/**
	 * Format the parameters.
	 * 
	 * @return string
	 */
	protected function formatParameters()
	{
		return Str::replaceLast(
			$this->delimiter,
			PHP_EOL,
			collect($this->parameters)->implode($this->delimiter)
		);
	}
}