<?php

namespace Sasin91\WoWEmulatorCommunication;

/**
 * Emulator Command Contract.
 */
interface EmulatorCommandContract
{
	/**
	 * Get the command string.
	 * 
	 * @return string
	 */
	public function command();

	/**
	 * Add an array of parameters to the command query.
	 * 
	 * @param array $parameters
	 */
    public function addParameters(array $parameters);
}