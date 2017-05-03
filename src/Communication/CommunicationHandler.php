<?php

namespace Sasin91\WoWEmulatorCommunication\Communication;

interface CommunicationHandler
{
    /**
     * Fire a command.
     *
     * @param string $command
     * @return mixed
     */
    public function handle($command);

    /**
     * Configure the Communicator.
     *
     * @param  string $client 
     * @param  array  $options
     * @return $this
     */
    public function configure($client, array $options);
}