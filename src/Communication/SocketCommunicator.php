<?php

namespace Sasin91\WoWEmulatorCommunication\Communication;

use Illuminate\Support\Arr;
use Sasin91\WoWEmulatorCommunication\Communication\CommunicationHandler;
use Sasin91\WoWEmulatorCommunication\Communication\Concerns\ParsesResponse;
use Socket\Raw\Factory;

class SocketCommunicator implements CommunicationHandler
{
    use ParsesResponse;

    /**
     * @var Factory
     */
    protected $socket;

    public function __construct(Factory $socket)
    {
        $this->socket = $socket;
    }

    /**
     * Configure the Communicator.
     *
     * @param  string $client 
     * @param  array  $options
     * @return $this
     */
    public function configure($name, $options)
    {
        $this->socket = $this->socket->createClient($options['location']);

        $this->socket->write($config['credentials']['login'].PHP_EOL)
        $this->socket->write($config['credentials']['password'].PHP_EOL);
    }

    /**
     * Fire a command.
     *
     * @param string $command
     * @return mixed
     */
    public function handle($command)
    {
        return $this->parseResponse($this->execute($command));
    }

    /**
     * Execute a given command
     * then return the raw result.
     *
     * @param $command
     * @return string
     */
    public function execute($command)
    {
        $this->socket->write($command.PHP_EOL);

        return $this->socket->read(1024);
    }
}