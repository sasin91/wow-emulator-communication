<?php

namespace Sasin91\WoWEmulatorCommunication\Communication;

use Artisaninweb\SoapWrapper\Client;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Sasin91\WoWEmulatorCommunication\EmulatorCommand;

class SoapHandler implements CommunicationHandler
{
    use Concerns\ParsesResponse;

    /**
     * The Soap wrapper.
     *
     * @var SoapWrapper
     */
    protected $soap;

    /**
     * Name of the Soap client.
     * 
     * @var string
     */
    protected $client;

    /**
     * SoapService constructor.
     *
     * @param SoapWrapper   $soap
     */
    public function __construct(SoapWrapper $soap)
    {
        $this->soap = $soap;
    }

    /**
     * Configure the Handler.
     *
     * @param  string $client 
     * @param  array  $options
     * @return $this
     */
    public function configure($client, array $options)
    {
        $this->client = $client;

        if (! $this->soap->has($this->client)) {
            $this->soap->add($this->client, function ($service) use($options) {
                $service
                    ->cache(WSDL_CACHE_MEMORY)
                    ->options($options);    
            });
        }

        return $this;
    }

    /**
     * Fire a SOAP Command.
     *
     * @param EmulatorCommand $command
     * @return string
     */
    public function handle($command)
    {
        return $this->parseResponse($this->execute($command));
    }
    
    /**
     * Execute a given command
     * then return the raw result.
     *
     * @param EmulatorCommand $command
     * @return string
     */
    public function execute($command)
    {
        return $this->soap->client($this->client, $this->executeCommandCallback($command));
    }

    /**
     * Callback for executing a Soap command with a SoapParam.
     * 
     * @param  EmulatorCommand $command
     * @return \Closure
     */
    protected function executeCommandCallback($command)
    {
        return function (Client $client) use ($command) {
            return $client->executeCommand(new \SoapParam($command, 'command'));
        };
    }

}